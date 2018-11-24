<div class="table-responsive">

<table class="table sortable table-responsive table-condensed table-striped table-bordered table-hover">


        <thead>
        <tr>
            <th>Role Name</th>
            <th>Category</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($roles as $role)
            <tr>
                <td><a href="{{url("roles/" . $role->id )}}">{{ $role->friendly_name or $role->name }}</a></td>
                <td>{{ $role->category or "Uncategorized" }}</td>
                <td>{{ $role->description }}</td>

            </tr>
        @endforeach
        </tbody>


</table>
    </div>