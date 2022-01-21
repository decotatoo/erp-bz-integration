@if ($view_type === 'report')
<div class="table-responsive ">
    <table class="table " id="productLists" width="100%">
        <thead>
            <th width="5px">No</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Qty/Box</th>
            <th>Price/Box</th>
            <th>Qty/Order</th>
            <th>Sub Total</th>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->qty_box }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->qty_order }}</td>
                <td>{{ $item->sub_total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@elseif ($view_type == 'invoice')
<div class="table-responsive ">
    <table class="table " id="productLists" width="100%">
        <thead>
            <th width="5px">No</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Price/Box</th>
            <th>Qty/Order</th>
            <th>Qty Release</th>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->qty_order }}</td>
                <td>{{ $item->qty_release }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-responsive ">
    <table class="table " id="productLists" width="100%">
        <thead>
            <th width="5px">No</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Qty Order</th>
            <th>Qty Release</th>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->qty_order }}</td>
                <td>{{ $item->qty_release }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif