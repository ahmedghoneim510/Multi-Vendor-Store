
<div class="form-group">
        {{--
                we can use either class="form-control  @error('name') is-invalid @enderror" or   @class(['form-control','is-invalid'=>@$error->has('name')])
                <input type="text" name="name" @class(['form-control','is-invalid'=>$errors->has('name')])  value="{{old('name',$category->name) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{'* '.$message}}
                    </div>
                @enderror
                 here we gonna use component    -- using : before value mean we don't need to write {{}}
         --}}
        <x-form.input label="Product Name" name="name"  :value="$product->name" type="text"/>
</div>
<div class="form-group">
    <label>Category </label>
    <select name="category_id" class="form-control form-select">
{{--        <option value="">Primary Category</option>--}}
        @foreach(App\Models\Category::all() as $category)
            <option value="{{$category->id}}" @selected(old('category_id', $product->category_id)== $category->id) >{{$category->name}}</option>
        @endforeach
    </select>
    @error('category_id')
    <div class="invalid-feedback">
        {{'* '.$message}}
    </div>
    @enderror
</div>
<div class="form-group">
    <label>Store</label>
    <select name="store_id" class="form-control form-select">
        @foreach(App\Models\Store::all() as $store)
            <option value="{{$store->id}}" @selected(old('store_id', $product->store_id)== $store->id) >{{$store->name}}</option>
        @endforeach
    </select>
    @error('store_id')
    <div class="invalid-feedback">
        {{'* '.$message}}
    </div>
    @enderror
</div>
<div class="form-group">
        {{--
            <label>Discription </label>
            <textarea name="discription" class="form-control">{{old('discription',$category->discription)}}</textarea>
            @error('discription')
            <div class="invalid-feedback">
                {{'* '.$message}}
            </div>
            @enderror
        --}}
    <x-form.textarea label="Discription" name="discription" :value="$category->discription" />
</div>
<div class="form-group">
    <x-form.label  id="image" >Image</x-form.label>
    <x-form.input  type="file" name="image" value="" accept="image/*" />
    @if (isset($category->image))
        <img src="{{asset('storage/'.$category->image)}}"  @style('margin:10px;') height="100px" >
    @endif
</div>
<div class="form-group">
    <label>Price</label>
    <div>
    <x-form.input name="price" :value="$product->price"  />
    </div>
</div>
<div class="form-group">
    <label>Compare Price</label>
    <div>
        <x-form.input name="compare_price" :value="$product->compare_price"  />
    </div>
</div>
<div class="form-group">
    <label>Tags</label>
    <div>
        <x-form.input name="tags" :value="$tags ??''"  />
    </div>
</div>
<div class="form-group">
    <label>Status </label>
    <div>
        <x-form.radio name="status"  checked="{{$category->status}}" :options="['active'=>'Active','draft'=>'Draft','archived'=>'Archived']" />
        {{--
<div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="active" @checked(old('status',$category->status)=='active')>
            <label class="form-check-label" >
                Active
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="archived" @checked(old('status',$category->status)=='archived')>
            <label class="form-check-label" >
                Archived
            </label>
        </div>
--}}

    </div>
</div>
<div class="form-group">
    <input type="submit" name="submit" value="{{$button_label ?? 'Save'}}" class="form-control btn btn-primary">
</div>
@push('styles')
    <link href="{{asset('dist/css/tagify.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
    <script src="{{asset('dist/js/tagify.js')}}"></script>
    <script src="{{asset('dist/js/tagify.polyfills.min.js')}}"></script>
    <script>
        var inputElm = document.querySelector('[name=tags]'),
            tagify = new Tagify (inputElm);
    </script>
@endpush
