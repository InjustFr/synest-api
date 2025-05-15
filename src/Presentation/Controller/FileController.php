<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Repository\FileRepositoryInterface;
use App\Core\Domain\Entity\File;
use App\Presentation\Security\Model\User;
use Assert\Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/files', name: 'app_files_')]
final class FileController extends AbstractController
{
    #[Route('/{file}', name: 'get', methods: ['GET'])]
    public function getFile(
        File $file,
    ): Response {
        return $this->file($file->getPath());
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addFile(
        #[MapUploadedFile(name: 'file')]
        UploadedFile $uploadedFile,
        #[CurrentUser]
        ?User $user,
        #[Autowire('%kernel.project_dir%/public/uploads')]
        string $uploadDirectory,
        SluggerInterface $slugger,
        FileRepositoryInterface $fileRepository,
    ): Response {
        if (null === $user) {
            throw $this->createAccessDeniedException();
        }

        Assert::that(file_exists($uploadedFile->getPathname()))
            ->true(\sprintf('Could not find uploaded file "%s".', $uploadedFile->getPathname()));

        $safeFilename = $slugger->slug(pathinfo($uploadedFile->getClientOriginalName(), \PATHINFO_FILENAME));
        $newFilename = $safeFilename.'-'.uniqid().'.'.($uploadedFile->guessExtension() ?? '');

        $newFile = $uploadedFile->move($uploadDirectory, $newFilename);
        Assert::that(file_exists($newFile->getPathname()))
            ->true(\sprintf('Could not move file to "%s".', $newFile->getPathname()));

        $file = File::create($newFile->getPathname(), $user->entity);
        $fileRepository->save($file);

        return $this->json(['file' => $this->generateUrl('app_files_get', [
            'file' => $file->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL)]);
    }

    #[Route('/{file}', name: 'delete', methods: ['DELETE'])]
    public function deleteFile(
        File $file,
        FileRepositoryInterface $fileRepository,
    ): Response {
        unlink($file->getPath());

        Assert::that(file_exists($file->getPath()))
            ->false(\sprintf('Could not delete file "%s".', $file->getPath()));

        $fileRepository->delete($file);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
