@extends('admin')
@section('head')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Zone Pricing in ({{ @$plan_name }}) Plan</h3>
                @can('add_pricing_plan')
                    <span class="btn btn-link text-blue font-weight-bold" type="button" id="addQueryBtn" data-bs-toggle="modal"
                        data-bs-target="#add-query">+ Add More</span>
                @endcan
            </div>
            <div class="card ">
                <div class="card-body p-0">
                    <table class="table emd-table1 table-borderless">
                        <tr>
                            <th>Sr.</th>
                            <th>Country</th>
                            <th>Country Code</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>Discount</th>
                            <th></th>
                        </tr>
                        @forelse ($emd_zone_pricings as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @can('edit_pricing_plan')
                                        <span type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-query{{ $item->id }}" style="cursor: pointer; color: rgb(43, 43, 182);">
                                            {{ @$item->emd_country->name ?? 'Country' }}
                                        </span>
                                    @else
                                        {{ @$item->emd_country->name ?? 'Country' }}
                                    @endcan
                                </td>
                                <td>{{ @$item->emd_country->code ?? 'Code' }} </td>
                                <td>{{ $item->price }} </td>
                                <td>{{ $item->sale_price }}</td>
                                <td>{{ $item->discount_percentage }}%</td>
                                <td class="actioncell">
                                    @can('delete_pricing_plan')
                                        <form action="{{ route('emd_destroy_zone_pricing', ['id' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger table-btn">TRASH</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th>Zone Price not available</th>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Zong Pricing Modal -->
    <div id="add-query" class="modal fade show" tabindex="-1" aria-labelledby="AddQueryLabel" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Zone Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </button>
                </div>
                <form action="{{ route('emd_create_zone_pricing', ['plan_id' => request()->route('plan_id')]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body add-query-body pb-0">
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Country</label>
                            <div class="col-5">
                                <select name="emd_country_id" id="" class="form-control">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Price</label>
                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">$</div>
                                    </div>
                                    <input type="number" class="form-control" id="inlineFormInputGroup" name="price"
                                        placeholder="Price" @required(true)>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Sale Price</label>
                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text border-right-0">$</div>
                                    </div>
                                    <input type="number" class="form-control" id="inlineFormInputGroup" placeholder="Price"
                                        name="sale_price" @required(true)>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Discount</label>
                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <input type="number" class="form-control border-right-0" id="inlineFormInputGroup"
                                        placeholder="Percent" name="discount_percentage" @required(true)>
                                    <div class="input-group-postpend">
                                        <div class="input-group-text border-left-0">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('add_pricing_plan')
                        <div class="modal-footer pt-0">
                            <button type="submit" class="btn btn-primary">Save Values</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    @foreach ($emd_zone_pricings as $emd_zone_pricing)
        <div id="edit-query{{ $emd_zone_pricing->id }}" class="modal fade show" tabindex="-1"
            aria-labelledby="AddQueryLabel" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Zone Price</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </button>
                    </div>
                    <form action="{{ route('emd_update_zone_pricing', ['id' => $emd_zone_pricing->id]) }}"
                        method="POST">
                        @csrf
                        <div class="modal-body add-query-body pb-0">
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Country</label>
                                <div class="col-5">
                                    <select name="emd_country_id" id="" class="form-control">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $country->id == $emd_zone_pricing->emd_country_id ? 'selected' : '' }}>
                                                {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Price</label>
                                <div class="col-5">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="inlineFormInputGroup"
                                            name="price" placeholder="Price" value="{{ $emd_zone_pricing->price }}"
                                            @required(true)>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Sale Price</label>
                                <div class="col-5">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text border-right-0">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="inlineFormInputGroup"
                                            placeholder="Price" name="sale_price"
                                            value="{{ $emd_zone_pricing->sale_price }}" @required(true)>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="colFormLabelSm" class="col-3 col-form-label col-form-label">Discount</label>
                                <div class="col-5">
                                    <div class="input-group mb-2">
                                        <input type="number" class="form-control border-right-0"
                                            id="inlineFormInputGroup" placeholder="Percent" name="discount_percentage"
                                            value="{{ $emd_zone_pricing->discount_percentage }}" @required(true)>
                                        <div class="input-group-postpend">
                                            <div class="input-group-text border-left-0">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('edit_pricing_plan')
                            <div class="modal-footer pt-0">
                                <button type="submit" class="btn btn-primary">Update Values</button>
                            </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('script')
@endsection
