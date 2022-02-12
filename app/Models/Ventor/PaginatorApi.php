<?php

namespace App\Models\Ventor;

class PaginatorApi
{
	protected $limit = 10;
	protected $page;
	protected $totalResults;
	protected $firstResult;
	protected $totalPages;
	protected $limitTotalPages = 5;
	protected $start;
	protected $end;
	protected $hasPrevious = false;
	protected $hasNext = false;
	protected $Router;

	public function __construct($total, $totalsPages, $page, $Router) {
		$this->page = $page;
		$this->Router = $Router;
		$this->totalPages = (int) $totalsPages;
		$this->totalResults = (int) $total;

		$this->firstResult = ($this->page * $this->limit) - $this->limit;

		if ($this->page - floor($this->limitTotalPages/2) > 0 and $this->totalPages > ($this->limitTotalPages-1)) {

			$this->start = $this->page - floor($this->limitTotalPages/2);
			if ($this->page + (($this->limitTotalPages/2)-1) > $this->totalPages) {

				$this->start= $this->totalPages - ($this->limitTotalPages-1);

			}

		}	else {

			$this->start = 1;

		}
		if ($this->totalPages < $this->limitTotalPages){

			$this->end = $this->totalPages;

		} else {

			$this->end = $this->start + ($this->limitTotalPages-1);

		}
		if ($this->page - 1 > 0){

			$this->hasPrevious = true;

		}
		if ($this->page + 1 <= $this->totalPages){

			$this->hasNext = true;

		}
	}

	protected function buildUrl($page = '') {

		$Router = $this->Router;
		$connect = '&';
		if (!str_contains($Router, '?')) {

			$connect = '?';

		}
		return $this->Router.$connect.'page='.$page;

	}

	public function getUrlPrevious() {

		$page = (int) $this->page - 1;
		if ($page == 0) {
			return null;
		}
		return $this->buildUrl($page);

	}

	public function getUrlNext() {

		$page = (int) $this->page + 1;
		return $this->buildUrl($page);

	}

	public function getUrlCurrent() {

		$page = (int) $this->page;
		return $this->buildUrl($page);

	}

	public function getUrlClean() {

		return $this->buildUrl();

	}

	public function gets() {

		return view(
			'components.public.paginator', array(
				'start' 		=> $this->start,
				'end' 			=> $this->end,
				'limit' 		=> $this->limit,
				'page' 			=> $this->page,
				'hasPrevious' 	=> $this->hasPrevious,
				'hasNext' 		=> $this->hasNext,
				'totalPages' 	=> $this->totalPages,
				'firstResult' 	=> $this->firstResult,
				'urls' => array(
					'next' 		=> $this->getUrlNext(),
					'current' 	=> $this->getUrlCurrent(),
					'clean' 	=> $this->getUrlClean(),
					'previous' 	=> $this->getUrlPrevious()
				)
			)
		)->render();

	}
}
