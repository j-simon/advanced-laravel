@extends('layouts.app')

@section('content')

<div class="flex items-center">

    <div class="md:w-1/2 md:mx-auto">

        <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

            <div class="w-full p-6">

                <form action="upload" method="post" enctype="multipart/form-data">

                    @csrf

                    <div class="sm:col-span-6 mt-6">

                        <input type="file" name="image" accept="image/*" />

                        @error('image')

                        <p class="text-red-500 text-xs italic">{{ $message }}</p>

                        @enderror

                    </div>
                    


                    <div class="mt-8 border-t border-gray-200 pt-5">

                        <div class="flex justify-end">

                            <span class="ml-3 inline-flex rounded-md shadow-sm">

                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">

                                    Save

                                </button>

                            </span>

                        </div>

                    </div>

                </form>
              

            </div>

        </div>

    </div>

</div>

@endsection