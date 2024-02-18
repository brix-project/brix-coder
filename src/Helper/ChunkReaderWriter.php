<?php

namespace Brix\Coder\Helper;

class ChunkReaderWriter
{

    public static function StringToChunk(string $input) : array
    {
        foreach(explode("\n\n", $input) as $idx => $chunk) {
            $chunks[$idx + 1] = $chunk;
        }
        return $chunks;
        
    }
    
    public static function ChunkUpdateFile(string $originalInput, array $updateChunks) : string {
        $chunks = self::StringToChunk($originalInput);
        foreach ($updateChunks as $idx => $chunk) {
            echo "Updating chunk $idx\n {$chunks[$idx]} \n => $chunk\n\n";
            $chunks[$idx] = $chunk;
        }
        return implode("\n\n", $chunks);
    }
    
}