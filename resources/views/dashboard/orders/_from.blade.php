
<div class="form-group">
    <label>Status</label>
    <div>
        <x-form.radio name="status"  checked="{{$order->status}}" :options="['pending'=>'Pending','processing'=>'Processing','delivering'=>'Delivering','completed'=>'Completed','canceled'=>'Canceled','refunded'=>'Refunded']" />
    </div>
</div>

<div class="form-group">
    <input type="submit" name="submit" value="{{$button_label ?? 'Save'}}" class="form-control btn btn-primary">
</div>
