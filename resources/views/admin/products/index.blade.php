@extends('layouts.admin')

@section('content')
<h1 class="mt-4">Products</h1>
<ol class="breadcrumb mb-4">
   <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
   <li class="breadcrumb-item active">Tables</li>
</ol>
<div class="card mb-4">
   <div class="card-header d-flex justify-content-between">
      <div>
         <i class="fas fa-table mr-1"></i>
         Products Table
      </div>
      <a type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createModal">
         <i class="fas fa-plus-circle"></i>
         <span>Add New Product</span>
      </a>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Image</th>
                  <th>description</th>
                  <th></th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse($products as $product)
               <tr>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->price }}</td>
                  <td><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 80px;"></td>
                  <td>{{ Str::limit($product->description,25,'...') }}</td>
                  <td><a type="button" class="badge badge-info dynamic-btn text-white" data-toggle="modal"
                        data-target="#createModal" data-product-id="{{ $product->id }}">Edit</a></td>
                  <td><a type="button" class="badge badge-danger dynamic-btn text-white" data-toggle="modal"
                        data-target="#deleteModal" data-product-id="{{ $product->id }}">Delete</a></td>
               </tr>
               @empty
               <tr>
                  <h3> No item in list yet </h3>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection

{{-- delete form --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Model to Delete the product"
   aria-hidden="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p id="deleteFormMessage"></p>
            <form action="" method="POST" id="deleteForm" enctype="multipart/form-data">
               @csrf
               @method('delete')
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger btn-sm text-white" onclick="submitDeleteForm()">Delete
               Product</button>
         </div>
      </div>
   </div>
</div>


{{-- create form --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="Model to create new product"
   aria-hidden="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="createModalLabel">Create Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
               @csrf

               <div class="form-group">
                  <label for="name" class="col-form-label">Name:</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="name"
                     value="{{ old('name') }}">
                  <p class="text-danger">@error('name') {{ $message }} @enderror</p>
               </div>
               <div class="form-group">
                  <label for="price" class="col-form-label">Price:</label>
                  <input type="text" name="price" class="form-control form-control-sm" id="price"
                     value="{{ old('price') }}">
                  <p class="text-danger">@error('price') {{ $message }} @enderror</p>
               </div>
               <div class="form-group" id="imageToEdit">
               </div>
               <div class="form-group">
                  <label for="image" class="col-form-label">Image:</label>
                  <input type="file" name="image" class="form-control-file" id="image">
                  <p class="text-danger">@error('image') {{ $message }} @enderror</p>
               </div>
               <div class="form-group">
                  <label for="description" class="col-form-label">Description:</label>
                  <textarea class="form-control form-control-sm" id="description" name="description"
                     rows="7">{{ old('description') }}</textarea>
                  <p class="text-danger">@error('description') {{ $message }} @enderror</p>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            <button type="button" onclick="submitProductForm()" class="btn btn-success btn-sm text-white"
               id="submitButton">Create
               Product</button>
         </div>
      </div>
   </div>
</div>

@if (count($errors) > 0)
<script type="text/javascript">
   // if there is some error in form, the modal shows itself
   document.addEventListener('readystatechange', function () {
      $('#createModal').modal('show')
   });
</script>
@endif

@section('scripts')
<script>

   const deleteForm = document.getElementById('deleteForm')
   const productForm = document.getElementById('productForm')
   const submitButton = document.getElementById('submitButton')

   // function called by modal button
   function submitDeleteForm() {
      deleteForm.submit()
   }

   // function called by modal button
   function submitProductForm() {
      productForm.submit()
   }

   // element to append in the form for update request
   const updateMethod = document.createElement('div')
   updateMethod.setAttribute('id', 'updateMethod')
   updateMethod.innerHTML = `
               <div id="updateMethod">
                  @method('put')
               </div>`

   /**
    * If product-id is passed through button pressed,
    *    fetch the product with that id from productController@show
    *    and populate the form with that product 
    *    and alter the action of form to update
    *
    */

   $('#createModal').on('show.bs.modal', async function (e) {
      var button = $(e.relatedTarget) // Button that triggered the modal
      var id = button.data('product-id') // Extract info from data-* attributes
      var modal = $(this)

      if (id) {
         submitButton.innerHTML = 'Update Product'
         submitButton.classList.replace('btn-success', 'btn-info')

         productForm.action = `{{ route('products.index') }}/${id}`
         productForm.appendChild(updateMethod)

         // fetching product with current id
         var url = "{{ route('products.index') }}"
         var product = await fetch(`${url}/${id}`).then(res => res.json())

         // populating the form
         const { name, price, image, description } = product

         modal.find('.modal-title').text('Edit product: ' + name)
         modal.find('.modal-body input#name').val(name)
         modal.find('.modal-body input#price').val(price)
         modal.find('.modal-body #imageToEdit').html(`<img src='/${image}' alt='${name}' style='max-width: 100%;'>`)
         modal.find('.modal-body textarea#description').val(description)
      }
   })

   /**
    * On closing the modal,
    *    reset the form and its action
    *  
    */

   $('#createModal').on('hide.bs.modal', function () {
      submitButton.innerHTML = 'Create Product'
      submitButton.classList.replace('btn-info', 'btn-success')

      var modal = $(this)
      document.getElementById('updateMethod')?.remove()

      modal.find('.modal-title').text('Create Product')
      modal.find('.modal-body input#name').val('')
      modal.find('.modal-body input#price').val('')
      modal.find('.modal-body #imageToEdit').html('')
      modal.find('.modal-body textarea#description').val('')
   })


   /**
    * On opening the delete modal
    *  
    */

   $('#deleteModal').on('show.bs.modal', async function (e) {
      var button = $(e.relatedTarget) // Button that triggered the modal
      var id = button.data('product-id') // Extract info from data-* attributes
      var modal = $(this)

      // fetching product with current id
      var url = "{{ route('products.index') }}"
      var product = await fetch(`${url}/${id}`).then(res => res.json())

      deleteForm.action = `{{ route('products.index') }}/${id}`

      modal.find('.modal-body #deleteFormMessage').html(`Do you really want to delete product: <strong>${product.name}</strong>`)
   })

</script>
@endsection
