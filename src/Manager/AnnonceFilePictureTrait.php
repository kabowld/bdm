<?php

namespace App\Manager;

use App\Entity\FilePicture;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

trait AnnonceFilePictureTrait {


    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureOneFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureTwoFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureThreeFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureFourFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureFiveFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureSixFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureSevenFile;

    /**
     * @Assert\Image(
     *     minWidth = 60,
     *     maxWidth = 1000,
     *     minHeight = 60,
     *     maxHeight = 1000,
     *     maxSize = "100M",
     *     maxSizeMessage = "La taille maximum de l'image doit être de {{ size }} {{ suffix }}",
     *     maxHeightMessage = "La hauteur maximum de l'image doit être de {{ max_width }}px",
     *     maxWidthMessage = "La largeur maximum de l'image doit être de {{ max_width }}px",
     *     minHeightMessage = "La hauteur maximum de l'image doit être de {{ min_height }}px",
     *     minWidthMessage = "La largeur maximum de l'image doit être de {{ min_width }}px",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Téléverser des images de type jpeg ou png !"
     * )
     */
    private $pictureHeightFile;

    /**
     * @return mixed
     */
    public function getPictureOneFile()
    {
        return $this->pictureOneFile;
    }

    /**
     * @param $file
     *
     * @return self
     */
    public function setPictureOneFile($file): self
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureOneFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureTwoFile()
    {
        return $this->pictureTwoFile;
    }

    /**
     * @param mixed $file
     *
     * @return AnnonceFilePictureTrait
     */
    public function setPictureTwoFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureTwoFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureThreeFile()
    {
        return $this->pictureThreeFile;
    }

    /**
     * @param mixed $file
     *
     * @return AnnonceFilePictureTrait
     */
    public function setPictureThreeFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureThreeFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureFourFile()
    {
        return $this->pictureFourFile;
    }

    /**
     * @param mixed $pictureFourFile
     * @return AnnonceFilePictureTrait
     */
    public function setPictureFourFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureFourFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureFiveFile()
    {
        return $this->pictureFiveFile;
    }

    /**
     * @param mixed $file
     *
     * @return AnnonceFilePictureTrait
     */
    public function setPictureFiveFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureFiveFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureSixFile()
    {
        return $this->pictureSixFile;
    }

    /**
     * @param mixed $file
     *
     * @return self
     */
    public function setPictureSixFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureSixFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureSevenFile()
    {
        return $this->pictureSevenFile;
    }

    /**
     * @param mixed $file
     *
     * @return AnnonceFilePictureTrait
     */
    public function setPictureSevenFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureSevenFile = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureHeightFile()
    {
        return $this->pictureHeightFile;
    }

    /**
     * @param mixed $file
     *
     * @return AnnonceFilePictureTrait
     */
    public function setPictureHeightFile($file)
    {
        $filePicture = new FilePicture();
        $filePicture->setFile($file);
        $this->addFilePicture($filePicture);

        $this->pictureHeightFile = $file;

        return $this;
    }
}
