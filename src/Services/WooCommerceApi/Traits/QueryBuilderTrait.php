<?php

namespace Decotatoo\Bz\Services\WooCommerceApi\Traits;

use Illuminate\Support\LazyCollection;

trait QueryBuilderTrait
{
    /**
     * @var
     */
    protected $options = [];

    /**
     * @var
     */
    protected $where = [];

    /**
     * @var
     */
    protected $properties = [];

    /**
     * @var
     */
    protected $isLazyCollection = false;

    /**
     * @var
     */
    protected $isCollection = true;

    /**
     * @var
     */
    protected $isOriginal = false;

    /**
     * Retrieve all Items.
     *
     * @param array $options
     *
     * @return array
     */
    public function all($options = [])
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->all($this->endpoint, $options));
        }

        if ($this->isCollection) {
            return collect($this->service->all($this->endpoint, $options));
        }

        return $this->service->all($this->endpoint, $options);
    }

    /**
     * Retrieve single Item.
     *
     * @param int   $id
     * @param array $options
     *
     * @return object
     */
    public function find($id, $options = [])
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->find("{$this->endpoint}/{$id}", $options));
        }

        if ($this->isCollection) {
            return collect($this->service->find("{$this->endpoint}/{$id}", $options));
        }

        return $this->service->find("{$this->endpoint}/{$id}", $options);
    }

    /**
     * Create new Item.
     *
     * @param array $data
     *
     * @return object
     */
    public function create($data)
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->create($this->endpoint, $data));
        }

        if ($this->isCollection) {
            return collect($this->service->create($this->endpoint, $data));
        }

        return $this->service->create($this->endpoint, $data);
    }

    /**
     * Update Existing Item.
     *
     * @param int   $id
     * @param array $data
     *
     * @return object
     */
    public function update($id, $data)
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->update("{$this->endpoint}/{$id}", $data));
        }

        if ($this->isCollection) {
            return collect($this->service->update("{$this->endpoint}/{$id}", $data));
        }

        return $this->service->update("{$this->endpoint}/{$id}", $data);
    }

    /**
     * Destroy Item.
     *
     * @param int   $id
     * @param array $options
     *
     * @return object
     */
    public function delete($id, $options = [])
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->delete("{$this->endpoint}/{$id}", $options));
        }

        if ($this->isCollection) {
            return collect($this->service->delete("{$this->endpoint}/{$id}", $options));
        }

        return $this->service->delete("{$this->endpoint}/{$id}", $options);
    }

    /**
     * Batch Update.
     *
     * @param array $data
     *
     * @return object
     */
    public function batch($data)
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->create("{$this->endpoint}/batch", $data));
        }

        if ($this->isCollection) {
            return collect($this->service->create("{$this->endpoint}/batch", $data));
        }

        return $this->service->create("{$this->endpoint}/batch", $data);
    }

    /**
     * Retrieve data.
     *
     * @return array
     */
    public function get()
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->service->all($this->endpoint, $this->options));
        }

        if ($this->isCollection) {
            return collect($this->service->all($this->endpoint, $this->options));
        }

        return $this->service->all($this->endpoint, $this->options);
    }

    /**
     * Retrieve data.
     *
     * @return object
     */
    public function first()
    {
        if ($this->isLazyCollection) {
            return LazyCollection::make($this->get()[0] ?? new \stdClass());
        }

        if ($this->isCollection) {
            return collect($this->get()[0] ?? new \stdClass());
        }

        return collect($this->get()[0] ?? new \stdClass());
    }

    /**
     * Set original.
     *
     * @return object $this
     */
    public function withOriginal()
    {
        $this->isOriginal = true;
        $this->isCollection = false;
        $this->isLazyCollection = false;

        return $this;
    }

    /**
     * Set collection.
     *
     * @return object $this
     */
    public function withCollection()
    {
        $this->isOriginal = false;
        $this->isCollection = true;
        $this->isLazyCollection = false;

        return $this;
    }

    /**
     * Set lazy collection.
     *
     * @return object $this
     */
    public function withLazyCollection()
    {
        $this->isOriginal = false;
        $this->isCollection = false;
        $this->isLazyCollection = true;

        return $this;
    }

    /**
     * Set options for woocommerce request.
     *
     * @param array $parameters
     *
     * @return object $this
     */
    public function options($parameters)
    {
        if (!is_array($parameters)) {
            throw new \Exception('Options must be an array', 1);
        }

        if (empty($parameters)) {
            throw new \Exception('Options must be pass at least one element', 1);
        }

        foreach ($parameters as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * Join options for woocommerce request.
     *
     * @param array $parameters
     *
     * @return object $this
     */
    public function where(...$parameters)
    {
        if (count($parameters) < 2 || count($parameters) > 3) {
            throw new \Exception('You can pass minimum 2 and maximum 3 paramneters');
        }
        $field = strtolower($parameters[0]);
        $value = count($parameters) == 3 ? $parameters[2] : $parameters[1];

        switch ($field) {
            case 'name': case 'title': case 'description':
                $this->options['search'] = $value;
                break;
            default:
                $this->options[$field] = $value;
                break;
        }

        return $this;
    }

    /**
     * Set order direction.
     *
     * @param string $name
     * @param string $direction
     *
     * @return object $this
     */
    public function orderBy($name, $direction = 'desc')
    {
        $this->options['orderby'] = $name;
        $this->options['order'] = $direction;

        return $this;
    }

    /**
     * Paginate results.
     *
     * @param int   $per_page
     * @param int   $current_page
     * @param array $options
     *
     * @return array
     */
    public function paginate($per_page = 10, $current_page = 1, $options = [])
    {
        try {
            $this->options['per_page'] = (int) $per_page;

            if ($current_page > 0) {
                $this->options['page'] = (int) $current_page;
            }

            foreach ($options as $option => $value) {
                $this->options[$option] = $value;
            }

            $data = $this->get();
            $totalResults = $this->service->countResults();
            $totalPages = $this->service->countPages();
            $currentPage = $this->service->current();
            $previousPage = $this->service->previous();
            $nextPage = $this->service->next();

            $pagination = [
                'total_results' => $totalResults,
                'total_pages'   => $totalPages,
                'current_page'  => $currentPage,
                'previous_page' => $previousPage,
                'next_page'     => $nextPage,
                'first_page'    => 1,
                'last_page'     => $totalResults,
            ];

            $results = [
                'meta'       => $pagination,
                'data'       => $data,
            ];

            if ($this->isLazyCollection) {
                return LazyCollection::make($results);
            }

            if ($this->isCollection) {
                return collect($results);
            }

            return $results;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 1);
        }
    }

    /**
     * Count all results.
     *
     * @return int
     */
    public function count()
    {
        try {
            $results = $this->service->all($this->endpoint, $this->options);
            $totalResults = $this->service->countResults();

            return $totalResults;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), 1);
        }
    }

    /**
     * Store data.
     *
     * @return array
     */
    public function save()
    {
        $this->results = $this->service->create($this->endpoint, $this->properties);

        if ($this->isLazyCollection) {
            return LazyCollection::make($this->results);
        }

        if ($this->isCollection) {
            return collect($this->results);
        }

        return $this->results;
    }
}
