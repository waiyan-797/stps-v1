@extends('layouts.app')

@section('content')
	<div class="container p-5">
		<div class="row">
			<div class="col-md-12">
				<form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-md-6">
							<div class=" mb-3">
								<label class="form-label" for="name">
									<span class="text-danger">*</span>{{ __('Username') }}
								</label>
								<input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text"
									value="{{ old('name') }}" required autocomplete="name" autofocus>
								@error('name')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class=" mb-3">
								<label class="form-label" for="email">
									<span class="text-danger">*</span>{{ __('Email Address') }}
								</label>
								<input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="text"
									value="{{ old('email') }}" required autocomplete="email" autofocus>
								@error('email')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class=" mb-3">
								<label class="form-label" for="phone">
									<span class="text-danger">*</span>{{ __('Phone Number') }}
								</label>
								<input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text"
									value="{{ old('phone') }}" required autocomplete="phone" autofocus>
								@error('phone')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="mb-3">
								<label class="form-label" for="birth_date">{{ __('Birth Date') }}</label>
								<input class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date"
									type="date" value="{{ old('birth_date') }}" autocomplete="birth_date">
								@error('birth_date')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>

						</div>
						<div class="col-md-6">
							<div class="row">
								<div class=" mb-3">
									<label class="form-label" for="driving_license">
										<span class="text-danger">*</span>{{ __('Driving License') }}
									</label>
									<input class="form-control @error('driving_license') is-invalid @enderror" id="driving_license"
										name="driving_license" type="text" value="{{ old('driving_license') }}" required
										autocomplete="driving_license" autofocus>
									@error('driving_license')
										<div class="invalid-feedback">
											{{ $message }}
										</div>
									@enderror
								</div>
								<div class=" mb-3">
									<label class="form-label" for="nrc_no">
										<span class="text-danger">*</span>{{ __('NRC Number') }}
									</label>
									<input class="form-control @error('nrc_no') is-invalid @enderror" id="nrc_no" name="nrc_no" type="text"
										value="{{ old('nrc_no') }}" required autocomplete="nrc_no" autofocus>
									@error('nrc_no')
										<div class="invalid-feedback">
											{{ $message }}
										</div>
									@enderror
								</div>
								<div class=" mb-3">
									<label class="form-label" for="password"><span class="text-danger">*</span>{{ __('Password') }}</label>
									<input class="form-control" id="password" name="password" type="password">
									@error('password')
										<div class="invalid-feedback">
											{{ $message }}
										</div>
									@enderror
								</div>
								<div class="mb-3">
									<label class="form-label" for="password-confirm"><span
											class="text-danger">*</span>{{ __('Confirm Password') }}</label>

									<input class="form-control" id="password-confirm" name="password_confirmation" type="password" required
										autocomplete="new-password">
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label" for="address">
								<span class="text-danger">*</span>{{ __('Address') }}
							</label>

							<textarea class=" form-control @error('address') is-invalid @enderror" id="address" name="address" required
							 autocomplete="address">{{ old('address') }}</textarea>

							@error('address')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label" for="vehicle_plate_no">{{ __('car type') }}</label>
							 <select name="type[]" id="" multiple class="form-control">
								<option disabled selected>Choose car type</option>

								@foreach($cartypes as $cartype)
									<option value="{{$cartype->id}}">{{$cartype->type}}</option>
								@endforeach
							 </select>
							@error('vehicle_plate_no')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror

						</div>
					</div>
					
						
					<div class="row align-items-end mb-3">
						<div class="col-md-6 mb-3">
							<label class="form-label" for="vehicle_plate_no">{{ __('Vehicle Plate No') }}</label>
							<input class="form-control @error('vehicle_plate_no') is-invalid @enderror" id="vehicle_plate_no"
								name="vehicle_plate_no" type="text" value="{{ old('vehicle_plate_no') }}"
								autocomplete="vehicle_plate_no">
							@error('vehicle_plate_no')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror

						</div>
						<div class="col-md-6 mb-3">
							<label class="form-label" for="vehicle_model">{{ __('Vehicle Model') }}</label>
							<input class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicle_model"
								name="vehicle_model" type="text" value="{{ old('vehicle_model') }}" autocomplete="vehicle_model">
							@error('vehicle_model')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
						</div>
						{{-- <div class="col-md-6">
							<div class="row m-0 justify-content-center">
								<div class="mt-2 col-md-8" id="profile_image-preview-container">

									<img class="img-fluid rounded" id="profile_image-preview" src="#" alt="Preview"
										style="display:none;">
								</div>
								<label class="form-label" for="profile_image">{{ __('Profile Photo') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="profile_image" name="profile_image" type="file" accept="image/*">
									<button class="btn btn-danger" id="profile_image-cancel-btn" type="button"
										style="display:none;">Cancel</button>
								</div>
								@error('vehicle_image')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="row m-0 justify-content-center">
								<div class="mt-2 col-md-8" id="vehicle_image-preview-container">

									<img class="img-fluid rounded" id="vehicle_image-preview" src="#" alt="Preview"
										style="display:none;">
								</div>
								<label class="form-label" for="vehicle_image">{{ __('Vehicle Photo') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="vehicle_image" name="vehicle_image" type="file" accept="image/*">
									<button class="btn btn-danger" id="vehicle_image-cancel-btn" type="button"
										style="display:none;">Cancel</button>
								</div>
								@error('vehicle_image')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div> --}}
					</div>
					{{-- <div class="row align-items-end mb-3">
						<div class="col-md-6">
							<div class="row m-0 justify-content-center ">
								<div class="mt-2 col-md-8" id="front_nrc-preview-container">
									<img class="img-fluid rounded" id="front_nrc-preview" src="#" alt="Preview" style="display:none;">
								</div>
								<label class="form-label" for="front_nrc">{{ __('Front NRC') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="front_nrc" name="front_nrc" type="file" accept="image/*">
									<button class="btn btn-danger" id="front_nrc-cancel-btn" type="button"
										style="display:none;">cancel</button>
								</div>
								@error('front_nrc')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="row m-0 justify-content-center">
								<div class="mt-2 col-md-8" id="back_nrc-preview-container">
									<img class="img-fluid rounded" id="back_nrc-preview" src="#" alt="Preview" style="display:none;">
								</div>
								<label class="form-label" for="back_nrc">{{ __('Back NRC') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="back_nrc" name="back_nrc" type="file" accept="image/*">
									<button class="btn btn-danger" id="back_nrc-cancel-btn" type="button" style="display:none;">Cancel</button>
								</div>
								@error('back_nrc')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="row m-0 justify-content-center">
								<div class="mt-2 col-md-8" id="front_license-preview-container">

									<img class="img-fluid rounded" id="front_license-preview" src="#" alt="Preview"
										style="display:none;">
								</div>
								<label class="form-label" for="front_license">{{ __('Front License') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="front_license" name="front_license" type="file" accept="image/*">
									<button class="btn btn-danger" id="front_license-cancel-btn" type="button"
										style="display:none;">cancel</button>
								</div>
								@error('front_license')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="row m-0 justify-content-center">
								<div class="mt-2 col-md-8" id="back_license-preview-container">

									<img class="img-fluid rounded" id="back_license-preview" src="#" alt="Preview"
										style="display:none;">
								</div>
								<label class="form-label" for="back_license">{{ __('Back License') }}</label>
								<div class="input-group p-0">
									<input class="form-control" id="back_license" name="back_license" type="file" accept="image/*">
									<button class="btn btn-danger" id="back_license-cancel-btn" type="button" style="display:none;">
										cancel
									</button>
								</div>
								@error('back_license')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div> --}}
					<div class="row mt-3">
						<div class="col-md-1 row m-0 mb-3 pe-lg-1">
							<button class="btn btn-danger " type="submit">Create</button>
						</div>
						<div class="col-md-1 row m-0 mb-3 pe-lg-1">
							<button class="btn btn-dark " type="reset">Cancel</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
@endsection
@push('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
		integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		// Front NRC Image Preview
		$("#front_nrc").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#front_nrc-preview").attr("src", reader.result).show();
				$("#front_nrc-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Front NRC Image Cancel
		$("#front_nrc-cancel-btn").click(function() {
			$("#front_nrc").val("");
			$("#front_nrc-preview").hide();
			$("#front_nrc-cancel-btn").hide();
		});

		// Back NRC Image Preview
		$("#back_nrc").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#back_nrc-preview").attr("src", reader.result).show();
				$("#back_nrc-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Back NRC Image Cancel
		$("#back_nrc-cancel-btn").click(function() {
			$("#back_nrc").val("");
			$("#back_nrc-preview").hide();
			$("#back_nrc-cancel-btn").hide();
		});

		// Front Licence Image Preview
		$("#front_license").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#front_license-preview").attr("src", reader.result).show();
				$("#front_license-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Front Licence Image Cancel
		$("#front_license-cancel-btn").click(function() {
			$("#front_license").val("");
			$("#front_license-preview").hide();
			$("#front_license-cancel-btn").hide();
		});

		// Back Licence Image Preview
		$("#back_license").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#back_license-preview").attr("src", reader.result).show();
				$("#back_license-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		});

		// Back Licence Image Cancel
		$("#back_license-cancel-btn").click(function() {
			$("#back_license").val("");
			$("#back_license-preview").hide();
			$("#back_license-cancel-btn").hide();
		});

		// Back Licence Image Preview
		$("#profile_image").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#profile_image-preview").attr("src", reader.result).show();
				$("#profile_image-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		})
		// Back Licence Image Cancel
		$("#profile_image-cancel-btn").click(function() {
			$("#profile_image").val("");
			$("#profile_image-preview").hide();
			$("#profile_image-cancel-btn").hide();
		});

		// Vehicle Image Preview
		$("#vehicle_image").change(function() {
			const reader = new FileReader();
			reader.onload = function() {
				$("#vehicle_image-preview").attr("src", reader.result).show();
				$("#vehicle_image-cancel-btn").show();
			};
			reader.readAsDataURL(this.files[0]);
		})
		// Vehicle Image Cancel
		$("#vehicle_image-cancel-btn").click(function() {
			$("#vehicle_image").val("");
			$("#vehicle_image-preview").hide();
			$("#vehicle_image-cancel-btn").hide();
		});
	</script>
@endpush
