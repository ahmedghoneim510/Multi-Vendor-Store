@if($errors->any())
    <div class="alert alert-danger">
        <h3>Eroor Occured..!</h3>
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </div>
@endif
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
        <x-form.input label="Category Name" name="name"  :value="$category->name" type="text"/>
</div>
<div class="form-group">
    <label>Category Parent</label>
    <select name="parent_id" class="form-control form-select">
        <option value="">Primary Category</option>
        @foreach($parents as $parent)
            <option value="{{$parent->id}}" @selected(old('parent_id', $category->parent_id)== $parent->id) >{{$parent->name}}</option>
        @endforeach
    </select>
    @error('parent_id')
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
    {{--    <label>Image </label>--}}
    {{--    <input type="file" name="image"  accept="image/*">--}}
    <x-form.label  id="image" >Image</x-form.label>
    <x-form.input  type="file" name="image" value="" accept="image/*" />
    @if (isset($category->image))
        <img src="{{asset('storage/'.$category->image)}}"  @style('margin:10px;') height="100px" >
    @endif
</div>
<div class="form-group">
    <label>Status </label>
    <div>
        <x-form.radio name="status"  checked="{{$category->status}}" :options="['active'=>'Active','inactive'=>'Archived']" />
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
