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

        if ($request->action === 'save-dd') {
            dd($request->all());
        }

        if (($model = $this->modelData()) && $model instanceof HasMedia) {
            $model->mediaManage($request);
        }
        
        if ($request->key) {
            session()->put($request->key, $request->value);
        }

        if ($tableOptions = $request->input('table-options')) {
            session()->put('table-options', $tableOptions);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'AJAX data saved!',
                'request' => $request->all(),
                'files' =>  $request->allFiles(),
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
