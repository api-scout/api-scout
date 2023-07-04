<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\Annotation\Annotation;
use function array_reduce;
use function assert;
use function explode;
use function sprintf;
use function strpos;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_STAR;

/**
 * @internal
 */
class SuppressHelper
{

	public const ANNOTATION = '@phpcsSuppress';

	public static function isSniffSuppressed(File $phpcsFile, int $pointer, string $suppressName): bool
	{
		return array_reduce(
			AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, self::ANNOTATION),
			static function (bool $carry, Annotation $annotation) use ($suppressName): bool {
				if ($annotation->getContent() === null) {
					return $carry;
				}

				$annotationSuppressName = explode(' ', $annotation->getContent())[0];

				if (
					$suppressName === $annotationSuppressName
					|| strpos($suppressName, sprintf('%s.', $annotationSuppressName)) === 0
				) {
					$carry = true;
				}
				return $carry;
			},
			false
		);
	}

	public static function removeSuppressAnnotation(File $phpcsFile, int $pointer, string $suppressName): void
	{
		$suppressAnnotation = null;
		foreach (AnnotationHelper::getAnnotationsByName($phpcsFile, $pointer, self::ANNOTATION) as $annotation) {
			if ($annotation->getContent() === $suppressName) {
				$suppressAnnotation = $annotation;
				break;
			}
		}

		assert($suppressAnnotation !== null);

		/** @var int $changeStart */
		$changeStart = TokenHelper::findPrevious($phpcsFile, T_DOC_COMMENT_STAR, $suppressAnnotation->getStartPointer() - 1);
		/** @var int $changeEnd */
		$changeEnd = TokenHelper::findNext(
			$phpcsFile,
			[T_DOC_COMMENT_CLOSE_TAG, T_DOC_COMMENT_STAR],
			$suppressAnnotation->getEndPointer() + 1
		) - 1;
		$phpcsFile->fixer->beginChangeset();
		FixerHelper::removeBetweenIncluding($phpcsFile, $changeStart, $changeEnd);
		$phpcsFile->fixer->endChangeset();
	}

}
