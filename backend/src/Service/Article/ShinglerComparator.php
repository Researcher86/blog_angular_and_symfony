<?php

declare(strict_types=1);

namespace App\Service\Article;

class ShinglerComparator implements ComparatorInterface
{
    /**
     * @var array<string>
     */
    private array $stopSymbols = [
        '.', ',', '!', '?', ':', ';', '-', '+', '\\', '|', '/', '~', '@', '\'', '^', '&', 'n', 'r', "\n", "\r",
        '(', ')', '$', '#', '%', '№',
    ];

    /**
     * @var array<string>
     */
    private array $stopWords = [
        'это', 'как', 'так', 'и', 'в', 'над', 'к', 'до', 'не', 'на', 'но', 'за', 'то', 'с', 'ли', 'а', 'во',
        'от', 'со', 'для', 'о', 'же', 'ну', 'вы', 'бы', 'что', 'кто', 'он', 'она',
    ];

    /**
     * @param array<string> $stopSymbols
     * @param array<string> $stopWords
     */
    public function __construct(array $stopSymbols = [], array $stopWords = [])
    {
        if ($stopSymbols) {
            $this->stopSymbols = $stopSymbols;
        }

        if ($stopWords) {
            $this->stopWords = $stopWords;
        }
    }

    public function compare(string $textA, string $textB, int $shingleLength = 5): float
    {
        $shinglesA = $this->shingle($this->canonize($textA), $shingleLength);
        $shinglesB = $this->shingle($this->canonize($textB), $shingleLength);

        $matches = \count(\array_intersect($shinglesA, $shinglesB));

        if ($matches === 0) {
            return 0;
        }

        return \round(2 * 100 * $matches / (\count($shinglesA) + \count($shinglesB)), 2);
    }

    /**
     * @return array<string>
     */
    private function canonize(string $text): array
    {
        $text = \strip_tags($text);
        $text = \str_replace($this->stopSymbols, '', $text);
        $text = (string) \preg_replace('/[0-9]/u', ' ', $text);
        $text = (string) \preg_replace('/(\s\s+)/', ' ', $text);
        $text = \mb_strtolower($text);

        $words = \explode(' ', $text);
        $result = \array_diff($words, $this->stopWords);

        return \array_values($result);
    }

    /**
     * @param array<string> $words
     *
     * @return array<int>
     */
    private function shingle(array $words, int $shingleLength): array
    {
        $result = [];

        $count = \count($words) - $shingleLength;

        for ($i = 0; $i <= $count; ++$i) {
            $currentShingle = [];

            for ($j = 0; $j < $shingleLength; ++$j) {
                $currentShingle[] = $words[$i + $j];
            }

            $shingledText = \implode(' ', $currentShingle);
            $result[] = \crc32($shingledText);
        }

        return $result;
    }
}
