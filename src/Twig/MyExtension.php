<?php

namespace App\Twig;

use Symfony\Component\Finder\Finder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function Symfony\Component\String\u;

class MyExtension extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			// If your filter generates SAFE HTML, you should add a third
			// parameter: ['is_safe' => ['html']]
			// Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
			new TwigFilter('DefaultImage', [$this, 'setImage']),
		];
	}

	public function getFunctions(): array
	{
		return [
			new TwigFunction('sum', [$this, 'sum']),
			new TwigFunction('avg', [$this, 'avg'])
		];
	}

	public function setImage($image = null): string
	{
		if ($image == null) return 'img/default.svg';
		dump($image);
		$finder = new Finder(); // looks up inside public directly
		$finder->in('../public/*')->exclude('build');
		$finder->files()->path($image);
		$finder->in('../public')->exclude('build');
		$finder->files()->path($image);
		if ($finder->hasResults())
			foreach ($finder as $file) {
				dump(u($file->getPathname())->after('../public/'));
				return  u($file->getPathname())->after('../public');
			}
		return 'img/default.svg';
	}

	public function sum(array $tab): int
	{
		$sum = 0;
		foreach ($tab as $e)
			$sum += $e;
		return $sum;
	}

	public function avg(array $tab): float
	{
		$count = 0;
		$sum = 0;
		foreach ($tab as $e) {
			$sum += $e;
			$count++;
		}
		return $sum / $count;
	}
}
