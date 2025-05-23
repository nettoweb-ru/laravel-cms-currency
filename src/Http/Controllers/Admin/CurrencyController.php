<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Response};

use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use Netto\Models\Currency as WorkModel;
use Netto\Http\Requests\Admin\CurrencyRequest as WorkRequest;

use Netto\Models\ExchangeRate;

class CurrencyController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'currency';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_currency',
        'create' => 'main.create_currency',
    ];

    protected string $itemRouteId = 'currency';

    protected array $viewId = [
        'list' => 'cms::currency.index',
        'edit' => 'cms::currency.currency',
    ];

    /**
     * @return Response
     */
    public function index(): Response
    {
        $this->addCrumbs();

        $rates = [];
        foreach (ExchangeRate::with(['source', 'target'])->get() as $model) {
            /** @var ExchangeRate $model */
            $rates[] = [
                'from' => format_currency($model->getAttribute('base'), $model->source->getAttribute('slug'), 0),
                'to' => format_currency($model->getAttribute('value'), $model->target->getAttribute('slug'), 4),
                'date' => format_date($model->getAttribute('date')),
                'provider' => $model->getAttribute('provider'),
            ];
        }

        return $this->view($this->viewId['list'], [
            'rates' => $rates,
        ]);
    }

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        $model = $this->createModel();
        return $this->redirect($model, $request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        $model = $this->getModel($id);
        return $this->redirect($model, $request);
    }

    /**
     * @param $model
     * @return array
     */
    protected function getReference($model): array
    {
        return [
            'boolean' => get_labels_boolean(),
        ];
    }
}
