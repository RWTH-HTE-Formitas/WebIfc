<?php

namespace WebIfc;

use Symfony\Component\Process\Process;

final class Converter
{
    /**
     * Converts the ifc file under the given path into a glTF file and returns its path.
     * The resulting file is temporary and removed on the end of the request.
     *
     * @param string $ifcFilePath
     * @return string
     */
    public function convertIfcToGlTF(string $ifcFilePath): string
    {
        $colladaFilePath = $this->convertIfcToCollada($ifcFilePath);
        $glTfFilePath = $this->convertColladaToGlTF($colladaFilePath);

        return $glTfFilePath;
    }

    /**
     * Converts the ifc file under the given path into a COLLADA file and returns its path.
     * The resulting file is temporary and removed on the end of the request.
     *
     * @param string $ifcFilePath
     * @return string
     */
    public function convertIfcToCollada(string $ifcFilePath): string
    {
        $targetFilePath = TemporaryFileFactory::getTemporaryFilePath('.dae');
        (new Process(['IfcConvert', $ifcFilePath, $targetFilePath]))->mustRun();

        return $targetFilePath;
    }

    /**
     * Converts the COLLADA file under the given path into a glTF file and returns its path.
     * The resulting file is temporary and removed on the end of the request.
     *
     * @param string $ifcFilePath
     * @return string
     */
    public function convertColladaToGlTF(string $colladaFilePath): string
    {
        $targetFilePath = TemporaryFileFactory::getTemporaryFilePath('.gltf');
        (new Process(['COLLADA2GLTF-bin', $colladaFilePath, $targetFilePath]))->mustRun();

        return $targetFilePath;
    }
}