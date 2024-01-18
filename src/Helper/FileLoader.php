<?php

namespace Brix\Coder\Helper;

use Phore\FileSystem\PhoreDirectory;

class FileLoader
{
    public array $gitIgnore = [];
    public function __construct(
        public PhoreDirectory|string $rootDir,
        public array $includePaths = [],
        public array $excludePaths = []

    ){
        $this->rootDir = phore_dir($rootDir);
        if ($this->rootDir->withFileName(".gitignore")->exists()) {
            $gitIgnore = phore_file($this->rootDir . "/.gitignore")->get_contents_array();
            foreach ($gitIgnore as $line) {
                if($line == "") continue;
                if (str_starts_with($line, "#")) continue;
                if (str_starts_with($line, "!")) continue;
                if (str_starts_with($line, "/")) $line = substr($line, 1);

                $this->gitIgnore[] = $line;
            }
        }


    }

    public function isExcluded(string $path) : bool
    {
        foreach ($this->excludePaths as $excludePath) {
            if (fnmatch($excludePath, $path))
                return true;
        }
        return false;
    }

    public function isGitIgnored(string $path) : bool
    {

        foreach ($this->gitIgnore as $line) {
            if (fnmatch($line, $path)) {
                return true;
            }
        }
        return false;
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
        $gitIgnore = phore_file($this->rootDir . "/.gitignore")->get_contents_array();
        $exampleDir = $this->rootDir;
        $files = [];
        foreach ($this->includePaths as $include) {
            if (phore_uri($exampleDir . "/". $include)->isFile()) {
                $files[] = [
                    "filename" => $include,
                    "content" => phore_dir($exampleDir . "/" . $include)->asFile()->get_contents()
                ];
                continue;
            }
            foreach (phore_dir($exampleDir . "/". $include)->listFiles(null, true) as $file) {
                // Skip files that are ignored by .gitignore

                if ($this->isGitIgnored($file->getRelPath())) {
                    echo "\n\nSkipping [.gitignore]: " . $file->getRelPath() . "\n";
                    continue;
                }
                if ($this->isExcluded($file->getRelPath())) {
                    echo "\n\nSkipping [exclude]: " . $file->getRelPath() . "\n";
                    continue;
                }

                $incUri = phore_uri("/" . $include . "/". $file->getRelPath())->clean();
                echo "\nIncluding: " . $incUri . "";
                $files[] = [
                    "path" => (string)$incUri,
                    "content" => $file->get_contents()
                ];
            }

        }
        return phore_json_pretty_print(json_encode($files));
    }


}
