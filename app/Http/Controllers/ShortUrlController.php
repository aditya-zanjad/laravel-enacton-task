<?php

namespace App\Http\Controllers;

use App\Builders\ShortUrlBuilder;
use App\DataTables\OtherUsersShortUrlsDataTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\ShortUrlsDataTable;
use App\DataTables\ShortURLsVisitsDataTable;

/**
 * This class contains CRUD methods for shortened links
 *
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 * @access  public
 */
class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShortUrlsDataTable $dataTable)
    {
        return $dataTable->render('short-urls.index');
    }

    /**
     * Display the paginated listing of short URLs by other users
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOthersShortUrls(OtherUsersShortUrlsDataTable $dataTable)
    {
        return $dataTable->render('short-urls.other-users-index');
    }

    /**
     * Get a paginated list of visits information to the particular URL
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\Response
     */
    public function indexVisits(mixed $id)
    {
        $validator = validator()->make(['id' => $id], [
            'id' => 'required|numeric|integer|exists:short_urls,id'
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'The selected short URL is invalid');
            return redirect()->route('short-urls.index');
        }

        $dataTable = new ShortURLsVisitsDataTable($id);
        return $dataTable->render('short-urls.visits');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('short-urls.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   \Illuminate\Http\Request                    $request
     * @param   \AshAllenDesign\ShortURL\Classes\Builder    $urlBuilder
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ShortUrlBuilder $urlBuilder)
    {
        $validator = validator()->make(
            $request->all(),
            [
                'url' => 'required|string|url'
            ],
            [
                'url.url'   =>  'The given URL is invalid'
            ]
        );

        if ($validator->fails()) {
            session()->flash('error', 'There are errors in your form');
            return redirect()
                ->route('short-urls.create')
                ->withErrors($validator->errors())
                ->withInput();
        }

        $validated          =   $validator->validated();  // Get validated form data
        $shortUrlCreated    =   $urlBuilder->destinationUrl($validated['url'])
            ->urlKey(Str::random(9))
            ->trackVisits()
            ->make();

        if (!$shortUrlCreated) {
            session()->flash('error', 'Failed to create the shortened URL');
            return redirect()->route('short-urls.create')->withInput();
        }

        session()->flash('success', 'The shortened URL is created successfully');
        return redirect()->route('short-urls.create');
    }
}
