<?php
namespace App\Helpers;
use Imagick;
use ImagickDraw;

/**
 * Class Image - Класс для обработки изображений
 */

class Image
{
    private $pathAvatarImg = "img/_tmp.jpg";
    private $pathBackgroundImg = 'img/fon.jpg';
    private $pathMaskImg = 'img/mask.png';
    private $pathOutputImg = 'img/complete.jpg';
    private $coords = [];

    private $backgroundImg = null;
    private $avatarImg = null;

    public function __construct()
    {
        $this->backgroundImg = new Imagick($this->pathBackgroundImg);
    }

    public function updateCoverPhoto(string $uriAvatar, string $fullName) : void
    {
        file_put_contents("img/_tmp.jpg", file_get_contents($uriAvatar));
        $this->avatarImg = new Imagick($this->pathAvatarImg);

        $this->coords = $this->getCoordsLocationAvatarImage();
        $this->setAvatarImgToBackgroundImg();
        $this->setTextUnderAvatar($fullName);

        $this->backgroundImg->writeImage($this->pathOutputImg);
    }

    private function setAvatarImgToBackgroundImg() : void
    {
        $this->setAvatarOptions();
        $this->backgroundImg->compositeImage($this->avatarImg, Imagick::COMPOSITE_ATOP, $this->coords['x'], $this->coords['y']);
    }

    private function setTextUnderAvatar(string $text) : void
    {
        $draw = new ImagickDraw();
        $draw->setFontSize(14);
        $draw->setFillColor('black');

        $draw->annotation($this->coords['x'] + 150, $this->coords['y'] + 55, "Новый пользователь:");
        $draw->annotation($this->coords['x'] + 150, $this->coords['y'] + 75, $text);

        $this->backgroundImg->drawImage($draw);
    }

    private function setAvatarOptions() : void
    {
        $mask = new Imagick($this->pathMaskImg);

        $this->avatarImg->cropThumbnailImage(125, 125);
        $this->avatarImg->compositeImage($mask, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
    }

    private function getCoordsLocationAvatarImage() : array
    {
        $bgcSize = $this->backgroundImg->getImageGeometry();
        $avatarSize = $this->avatarImg->getImageGeometry();

        return [
            'x' => $bgcSize['width'] / 2 - $avatarSize['width'] / 2,
            'y' => $bgcSize['height'] / 2 - $avatarSize['height'] / 2
        ];
    }
}