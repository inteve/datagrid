# Inteve\Datagrid

[![Build Status](https://github.com/inteve/datagrid/workflows/Build/badge.svg)](https://github.com/inteve/datagrid/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/inteve/datagrid.svg)](https://packagist.org/packages/inteve/datagrid)
[![Latest Stable Version](https://poser.pugx.org/inteve/datagrid/v/stable)](https://github.com/inteve/datagrid/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/inteve/datagrid/blob/master/license.md)

DataGrid component for Nette.

<a href="https://www.janpecha.cz/donate/"><img src="https://buymecoffee.intm.org/img/donate-banner.v1.svg" alt="Donate" height="100"></a>


## Installation

[Download a latest package](https://github.com/inteve/datagrid/releases) or use [Composer](http://getcomposer.org/):

```
composer require inteve/datagrid
```

Inteve\Datagrid requires PHP 8.0 or later.


## Usage

In presenter:

``` php
class MyPresenter extends Nette\Application\UI\Presenter
{
	protected function createComponentGrid()
	{
		$datasource = new Inteve\DataGrid\DataSources\LeanMapperQuery($this->repository->queryAll(), $this->mapper);
		$grid = new Inteve\DataGrid\DataGrid($datasource);
		$grid->setTemplateFile(__DIR__ . '/@grid.latte'); // optional
		$grid->setItemsOnPage(20, TRUE); // optional

		$grid->addTextColumn('title', 'Title')
			->setCustomRender(function (Entity\Post $post) {
				$label = Html::el();
				$label->addText($post->title);
				return $label;
			})
			->setSortable();

		$grid->addLinkColumn('url', 'URL');

		$grid->addDateColumn('date', 'Date')
			->setSortable();

		$grid->addNumberColumn('views', 'Views')
			->setSortable()
			->setDecimals(1)
			->setValueProvider(function (Entity\Post $post) {
				return max(1, $post->views);
			});

		$grid->addAction('edit', 'Upravit', $this->lazyLink('edit'));

		$grid->addAction('delete', 'Smazat', $this->lazyLink('delete!'));

		$grid->addTextFilter('title', 'Title');

		$grid->addTextFilter('url', 'URL');

		$grid->setDefaultSort(array(
			'date' => 'DESC',
			'title' => 'ASC',
		));

		return $grid;
	}
}
```

In template:

```latte
{control grid}
```

------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
