<?php

namespace Brix\Coder\Helper;

use Phore\FileSystem\PhoreDirectory;

class ExampleLoader
{
    public array $gitIgnore = [];
    public function __construct(
        public PhoreDirectory|string $rootDir,
        public array $includePaths = ["./node_modules/", "./vendor/"],

    ){
        $this->rootDir = phore_dir($rootDir);

    }


    /**
     * Generate a list of files and their content
     *
     * @return array An array of filenames and their corresponding content
     *
     * @throws \Phore\FileSystem\Exception\FileAccessException
     * @throws \Phore\FileSystem\Exception\FileNotFoundException
     */
    public function generateFileContent(bool $string = false) : string {
        // Load contents of .gitignore

        $exampleDir = $this->rootDir;
        $files = [];
        foreach ($this->includePaths as $include) {
            $include = $this->rootDir->withRelativePath($include)->assertDirectory(true);
            if ( ! $include->isDirectory()) continue;

            foreach ($include->list(null, true, 1) as $file) {
                // Skip files that are ignored by .gitignore

                if ( ! $file->isDirectory()) continue;
                echo "\n$file";
                $brixFile = $file->withRelativePath(".brix.yml");
                if ( ! $brixFile->isFile()) continue;

                $data = $brixFile->asFile()->get_yaml();

                $ex = $data["coder"]["example_dir"] ?? null;
                if ($ex === null) {
                    continue;
                }

                $examples = $file->withRelativePath($ex)->assertDirectory(true);

                foreach ($examples->listFiles(null, true) as $exampleFile) {
                    if ($exampleFile->isDirectory()) continue;
                    $files[] = [
                        "path" => (string)$exampleFile->rel($exampleDir),
                        "content" => ChunkReaderWriter::StringToChunk($exampleFile->get_contents())
                    ];
                }
            }

        }
        return phore_json_pretty_print(json_encode($files));
    }


}
