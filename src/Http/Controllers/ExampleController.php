<?php

namespace Fomvasss\Lte3\Http\Controllers;

use Exception;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExampleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function components()
    {
        \Session::flash('info', 'Welcome to Admin LTE Components!');

        if (!rand(0, 5)) {
            \Session::flash('callout.warning', 'This is random callout!');
        }

        return view('lte3::examples.components', [
            'treeviewArray' => $this->treeviewStaticData(),
            'model' => $this->modelData(),
            'progects' => $this->projectsData(),
            //'terms' => \App\Models\Term::whereIn('vocabulary', ['brands', 'models'])->paginate(8),
        ]);
    }
    
    public function mbBlocks(Request $request)
    {
        return view('lte3::examples.mb-blocks');
    }

    /**
     * Форма з опису полів (Lte3::fields): POST перевіряє поля й повертає на форму з old() або з надісланими значеннями
     */
    public function fields(Request $request)
    {
        $values = [
            'title' => 'Sales',
            'notify' => true,
            'pipelines' => [
                ['id' => 'retail', 'status_id' => 'retail_new', 'hint' => 'Private customers'],
            ],
            'default_pipeline_id' => 'retail',
        ];

        if ($request->isMethod('post')) {
            $values = $request->validate([
                'settings.title' => ['required', 'string', 'max:100'],
                'settings.pipelines' => ['required', 'array', 'min:1'],
                'settings.pipelines.*.id' => ['required', 'string'],
                'settings.pipelines.*.status_id' => ['nullable', 'string'],
                'settings.pipelines.*.hint' => ['nullable', 'string'],
                'settings.default_pipeline_id' => ['required', 'string'],
                'settings.tags' => ['nullable', 'array'],
                'settings.notify' => ['nullable', 'boolean'],
            ])['settings'];

            \Session::flash('success', 'Submitted: ' . json_encode($values, JSON_UNESCAPED_UNICODE));
        }

        $pipelines = collect($this->pipelinesData())->pluck('text', 'id')->all();

        return view('lte3::examples.fields', [
            'values' => $values,
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true],
                ['name' => 'pipelines', 'type' => 'list', 'label' => 'Pipelines', 'min' => 1, 'max' => 5, 'add_label' => 'Add pipeline', 'fields' => [
                    ['name' => 'id', 'type' => 'select', 'label' => 'Pipeline', 'url' => route('lte3.data.pipelines'), 'options' => $pipelines, 'required' => true],
                    ['name' => 'status_id', 'type' => 'select', 'label' => 'Status', 'url' => route('lte3.data.pipeline-statuses'), 'depends_on' => 'id', 'options' => $this->statusesOf(array_column($values['pipelines'] ?? [], 'id'))],
                    ['name' => 'hint', 'type' => 'text', 'label' => 'When to choose', 'placeholder' => 'Cafes, restaurants'],
                ]],
                ['name' => 'default_pipeline_id', 'type' => 'select', 'label' => 'Default pipeline', 'options' => $pipelines, 'required' => true],
                ['name' => 'tags', 'type' => 'multiselect', 'label' => 'Tags', 'options' => ['new' => 'New', 'vip' => 'VIP', 'wholesale' => 'Wholesale']],
                ['name' => 'notify', 'type' => 'checkbox', 'label' => 'Notify on new items'],
            ],
        ]);
    }

    public function pipelines(Request $request)
    {
        $term = mb_strtolower((string) $request->input('term'));

        return response()->json(['results' => array_values(array_filter($this->pipelinesData(), fn (array $item) => $term === '' || str_contains(mb_strtolower($item['text']), $term)))]);
    }

    /**
     * Залежний вибір: статуси воронки з `?id=` (depends_on: id)
     */
    public function pipelineStatuses(Request $request)
    {
        $statuses = $this->statusesOf([(string) $request->input('id')]);

        return response()->json(['results' => array_map(fn ($id, $text) => ['id' => $id, 'text' => $text], array_keys($statuses), $statuses)]);
    }

    private function pipelinesData(): array
    {
        return [
            ['id' => 'retail', 'text' => 'Retail'],
            ['id' => 'horeca', 'text' => 'HoReCa'],
        ];
    }

    private function statusesOf(array $pipelineIds): array
    {
        $all = [
            'retail' => ['retail_new' => 'Retail / New', 'retail_paid' => 'Retail / Paid'],
            'horeca' => ['horeca_new' => 'HoReCa / New', 'horeca_contract' => 'HoReCa / Contract'],
        ];

        return array_merge(...array_map(fn ($id) => $all[$id] ?? [], $pipelineIds ?: ['']));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function treeselect(Request $request)
    {
        return response()->json([
            'data' => $this->nestedData(),
            'selected' => $request->get('selected', []),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function treeview(Request $request)
    {
        return response()->json([
            'data' => treeview($this->nestedData(), $request->get('selected', []))
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        if ($request->key && $request->value_key) {
            session()->put($request->key, $request->{$request->value_key});
            session()->save();
        } elseif ($request->key) {
            session()->put($request->key, $request->value);
        }

        if ($request->action === 'save-dd') {
            dd($request->all());
        }

        if (($model = $this->modelData()) && $model instanceof HasMedia) {
            $model->mediaManage($request);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'AJAX data saved!',
                'request' => $request->all(),
                'files' =>  $request->allFiles(),
                '_action' => $request->_action,
            ]);
        }

        return redirect()->back()->with('success', 'Form data saved!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function statuses()
    {
        return response()->json(['results' => [
            ['id' => 1, 'text' => 'Pending',],
            ['id' => 2, 'text' => 'Canceled',],
            ['id' => 3, 'text' => 'Delivered',],
            ['id' => 4, 'text' => 'Approved',],
        ]]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags(Request $request)
    {
        return response()->json([
            'results' => [
                [
                    'id' => '1',
                    'text' => 'News',
                ],
                [
                    'id' => '2',
                    'text' => 'Scince',
                ],
                [
                    'id' => '3',
                    'text' => 'Sport',
                ],
                [
                    'id' => '4',
                    'text' => 'Auto',
                ],
                [
                    'id' => '5',
                    'text' => 'Weather',
                ],
                [
                    'id' => '6',
                    'text' => 'Economy',
                ],
                [
                    'id' => '7',
                    'text' => 'Nature',
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalContent(Request $request)
    {
        return response()->json([
            'html' => view('lte3::examples.modals.modal1')->render(),
        ]);
    }

    /**
     * @return array[]
     */
    protected function nestedData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Auto',
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'Music',
                        'children' => [],
                    ],
                    [
                        'id' => 3,
                        'name' => 'Tuning',
                        'children' => [],
                    ],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Food',
                'children' => [],
            ],
            [
                'id' => 5,
                'name' => 'Sport',
                'children' => [],
            ],
        ];
    }

    protected function treeviewStaticData()
    {
        return [
            [
              'id' => 1,
              'text' => 'Parent 1',
              'href' => '#parent1',
              'tags' => ['4'],
              'nodes' => [
                [
                  'id' => 11,
                  'text' => 'Child 1',
                  'href' => '#child1',
                  'tags' => ['2'],
                  'state' => ['expanded' => true, 'checked' => true],
                  'nodes' => [
                    [
                      'id' => 111,
                      'text' => 'Grandchild 1',
                      'href' => '#grandchild1',
                      'tags' => ['0'],
                    ],
                    [
                      'id' => 112,
                      'text' => 'Grandchild 2',
                      'href' => '#grandchild2',
                      'tags' => ['0'],
                    ],
                  ],
                ],
                [
                  'id' => 12,
                  'text' => 'Child 2',
                  'href' => '#child2',
                  'tags' => ['0'],
                ],
              ],
            ],
            [
              'id' => 2,
              'text' => 'Parent 2',
              'href' => '#parent2',
              'tags' => ['0'],
            ],
            [
              'id' => 3,
              'text' => 'Parent 3',
              'href' => '#parent3',
              'tags' => ['0'],
            ],
            [
              'id' => 4,
              'text' => 'Parent 4',
              'href' => '#parent4',
              'tags' => ['0'],
            ],
            [
              'id' => 5,
              'text' => 'Parent 5',
              'href' => '#parent5',
              'tags' => ['0'],
            ],
          ];
    }

    protected function modelData()
    {
        try {
            return $user = \App\Models\User::first();
        } catch (Exception $e) {

        }

        return null;
    }

    protected function projectsData()
    {
        return [
            ['name' => 'Admin LTE v3', 'status' => 'Success', 'created_at' => now()->toDateString(), 'sum' => 12400, 'progress' => 57, 'images' => ['vendor/adminlte/dist/img/avatar.png', 'vendor/adminlte/dist/img/avatar2.png', 'vendor/adminlte/dist/img/avatar3.png', 'vendor/adminlte/dist/img/avatar4.png'],],
            ['name' => 'Laravel 10.0', 'status' => 'Wait', 'created_at' => now()->subDays(8)->toDateString(), 'sum' => 530, 'progress' => 35, 'images' => ['vendor/adminlte/dist/img/avatar4.png', 'vendor/adminlte/dist/img/avatar3.png'],],
            ['name' => 'PHP v8.3', 'status' => 'Completed', 'created_at' => now()->subDay(15)->toDateString(), 'sum' => 5640, 'progress' => 95, 'images' => ['vendor/adminlte/dist/img/avatar3.png', 'vendor/adminlte/dist/img/avatar.png', 'vendor/adminlte/dist/img/avatar2.png',],],
        ];
    }
}
