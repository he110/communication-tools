<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 12/08/2019
 * Time: 22:40
 */

namespace He110\CommunicationTools\ScreenItems;


use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;

class File implements ScreenItemInterface
{
    const FILE_TYPE_IMAGE = "image";
    const FILE_TYPE_DOCUMENT = "document";

    /** @var string|null */
    private $path;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $type;

    /** @var int|null */
    private $size;

    /** @var string */
    private $description;

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path ?? null;
    }

    /**
     * @param string $path
     * @return File
     */
    public function setPath(string $path): self
    {
        if (file_exists($path)) {
            $this->setSize(filesize($path));
            if (is_null($this->getName()))
                $this->setName(basename($path));
            if (is_null($this->getType()))
                $this->setType($this->isImage($path) ? static::FILE_TYPE_IMAGE : static::FILE_TYPE_DOCUMENT);
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $urlPath
     * @return File
     * @throws AttachmentNotFoundException
     */
    public function setUrlPath(string $urlPath): self
    {
        $path = $this->downloadFile($urlPath);
        $this->setPath($path);
        $this->path = $urlPath;
        unlink($path);
        return $this;
    }

    /**
     * @param string $from
     * @return string
     * @throws AttachmentNotFoundException
     */
    private function downloadFile(string $from): string
    {
        $saveName = $this->parseFileName($from);
        $fp = fopen($saveName, 'w+');
        if($fp === false){
            throw new AttachmentNotFoundException("Attachment can't be downloaded from url. Url is invalid");
        }
        $ch = curl_init($from);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        if($statusCode == 200){
            return $saveName;
        } else{
            throw new AttachmentNotFoundException("Attachment can't be downloaded from url. Status from web: $statusCode");
        }
    }

    /**
     * @param string $url
     * @return string
     * @throws AttachmentNotFoundException
     */
    private function parseFileName(string $url): string
    {
        $fileName = [];
        preg_match('/[^\/\&\?]+\.\w{3,4}(?=([\?&].*$|$))/m', $url, $fileName);
        if (isset($fileName[0]))
            return $fileName[0];
        throw new AttachmentNotFoundException("File name cannot be parsed");
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size ?? 0;
    }

    /**
     * @param int $size
     */
    private function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? "";
    }

    /**
     * @param string $description
     * @return File
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return array(
            "path" => $this->getPath(),
            "name" => $this->getName(),
            "size" => $this->getSize(),
            "type" => $this->getType(),
            "description" => $this->getDescription()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromArray(array $data)
    {
        if (isset($data["path"]))
            $this->setUrlPath($data["path"]);
        foreach($data as $key=>$value) {
            $methodName = "set".ucfirst($key);
            if (method_exists($this, $methodName) && is_callable([$this, $methodName]))
                $this->{$methodName}($value);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    static public function create(array $config)
    {
        return (new File())->fromArray($config);
    }

    /**
     * @return null|string
     */
    public function __toString(): string
    {
         return $this->getName();
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isImage(string $path): bool
    {
        $a = getimagesize($path);
        $imageType = $a[2];

        if (in_array($imageType , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
            return true;
        return false;
    }
}