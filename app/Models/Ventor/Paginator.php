<?php

namespace App\Models\Ventor;

class Paginator
{
	protected $requestParams;
	protected $limit = 10;
	protected $page;
	protected $totalResults;
	protected $firstResult;
	protected $totalPages;
	protected $limitTotalPages = 5;
	protected $start;
	protected $end;
	protected $hasAnterior = false;
	protected $hasSiguiente = false;
	protected $Router;

	public function __construct($params, $total, $Router) {}
}
