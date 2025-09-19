<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\ItemRequest;
use App\Models\items\Item;
use App\Models\Items\ItemCategory;
use App\Models\Items\ItemImage;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Exception;
use PhpParser\Node\Expr\Cast\Object_;
use Psy\Util\Json;
use function PHPUnit\Framework\isEmpty;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
//        $items = Item::with(['category' => function ($query){
//                $query->select(['id', 'title']);
//            }])
//            ->get(['item_category_id', 'name', 'part_no', 'unit', 'purchase_price', 'sale_price', 'status' ]);

        $items = Item::with(['category:id,title', 'images:item_id,file_name'])
            ->get(['id', 'item_category_id', 'name', 'part_no', 'unit', 'purchase_price', 'sale_price', 'status' ]);

        $parentCategories = ItemCategory::whereNull('parent_id')->get();

        return view('items.items', compact('items', 'parentCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $parentCategories = ItemCategory::whereNull('parent_id')->get();

        return view('items.create-item', compact('parentCategories'));
    }

    public function getSubCategories($id): JsonResponse
    {
        $categories = ItemCategory::whereParentId($id)->get(['id', 'title']);

        return response()->json($categories);
    }

    function nl2br2($string)
    {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
        return $string;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $item = Item::create(
                    $request->validated(),
//                    'name' => $request->input('name'),
//                    'part_no' => $request->input('part_no'),
//                    'description' => $this->nl2br2($request->input('description')),
//                    'unit' => $request->input('unit'),
//                    'item_category_id' => $request->input('item_category_id'),
//                    'purchase_price' => $request->input('purchase_price'),
//                    'sale_price' => $request->input('sale_price'),
                    );

                if($request->hasFile('image_files'))
                {
                    foreach ($request->file('image_files') as $key => $file)
                    {
                        $fileName = $item->id.'-'.uniqid().'.'.$file->extension();
                        $file->storeAs('public/item_images/', $fileName);

                        ItemImage::create([
                            'item_id' => $item->id,
                            'file_name' => $fileName,
                        ]);
                    }
                }
            });
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }

        notify()->success('Item added successfully', 'Item Added');
        return redirect('items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show(int $id): View
    {
        $item = Item::with(['category:id,parent_id,title', 'images:id,item_id,file_name'])
            ->whereId($id)
            ->get();

        return view('items.show-item', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit(Item $item, int $id): View
    {
         $item = Item::with(['category:id,parent_id,title', 'images:id,item_id,file_name'])
             ->whereId($id)
             ->get();
        $parentCategories = ItemCategory::whereNull('parent_id')->get();

//        $fileExtension = [];
//        foreach ($item[0]->images as $key => $file)
//        {
//            $infoPath = pathinfo(public_path('/item_images/'.$file->file_name));
//            $extension = $infoPath['extension'];
//            $fileExtension[] = $extension;
//        }

        return view('items.edit-item', compact('item', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, int $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $item = Item::findOrFail($id);

                if(!empty($item))
                {
                    //Update Item
                    $item->update($request->validated(),
//                        $item->name => $request->input('name'),
//                        $item->part_no => $request->input('part_no'),
//                        $item->description => $this->nl2br2($request->input('description')),
//                        $item->unit => $request->input('unit'),
//                        $item->item_category_id => $request->input('item_category_id'),
//                        $item->purchase_price => $request->input('purchase_price'),
//                        $item->sale_price => $request->input('sale_price')
                    );

                    //Remove ItemFile from Storage and DB Table
                    if (!empty($request->input('chkFileIds')))
                    {
                        //dd($request->input('chkFileIds'));
                        $itemFiles = ItemImage::findOrFail($request->input('chkFileIds'));

                        foreach ($itemFiles as $itemFile)
                        {
                            Storage::delete('public/item_images/'.$itemFile->file_name);
                            $itemFile->delete();
                        }
                    }

                    //Add New ItemFiles
                    if($request->hasFile('image_files'))
                    {
                        //dd('dfdfd');
                        foreach ($request->file('image_files') as $key => $file)
                        {
                            $fileName = $item->id.'-'.uniqid().'.'.$file->extension();
                            $file->storeAs('public/item_images/', $fileName);

                            ItemImage::create([
                                'item_id' => $item->id,
                                'file_name' => $fileName,
                            ]);
                        }
                    }
                }
            });
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }

        notify()->success('Item Updated successfully', 'Item Updated');
        return redirect('items');
    }
}
