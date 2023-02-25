<?php

namespace Fomvasss\Lte3\Http\Controllers;

use App\Models\Term;
use App\Models\User;
use Exception;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExampleController extends Controller
{

    public function fields()
    {
        \Session::flash('info', 'Welcome to Laravel Admin LTE!');

        return view('lte3::examples.fields', [
            'treeviewArray' => $this->treeviewStaticArray(),
            'model' => $this->getModel(),
            'terms' => Term::get(),
        ]);
    }

    public function treeselect(Request $request)
    {
        return response()->json([
            'data' => $this->getNested(),
            'selected' => $request->get('selected', []),
        ]);
    }

    public function treeview(Request $request)
    {
        return response()->json([
            'data' => treeview($this->getNested(), $request->get('selected', []))
        ]);
    }

    public function save(Request $request)
    {

        if (($model = $this->getModel()) && $model instanceof HasMedia) {
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

    public function statuses()
    {
        return response()->json(['results' => [
            ['id' => 1, 'text' => 'Pending',],
            ['id' => 2, 'text' => 'Canceled',],
            ['id' => 3, 'text' => 'Delivered',],
            ['id' => 4, 'text' => 'Approved',],
        ]]);
    }

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

    public function modalContent(Request $request)
    {
        return response()->json([
            'html' => '<div class="modal-header"><h4 class="modal-title">Small Modal</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>AJAX data</p></div><div class="modal-footer justify-content-between"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button></div>',
        ]);
    }

    protected function getNested()
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

    protected function treeviewStaticArray()
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

    public function getModel()
    {
        try {
            return $user = User::first();
        } catch (Exception $e) {

        }

        return null;
    }

}
