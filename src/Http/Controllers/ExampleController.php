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

        return view('lte3::examples.components', [
            'treeviewArray' => $this->treeviewStaticData(),
            'model' => $this->modelData(),
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
        if (($model = $this->modelData()) && $model instanceof HasMedia) {
            $model->mediaManage($request);
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
            'html' => '<div class="modal-header"><h4 class="modal-title">Small Modal</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>AJAX data</p></div><div class="modal-footer justify-content-between"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button></div>',
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
        return $ar = [
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

}
