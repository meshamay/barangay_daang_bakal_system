@extends('layouts.app')


@section('content')

<!--NEXT PAGE-->
<main class="fflex-1 p-11 fixed top-[60px] left-[220px]
    w-[calc(100vw-200px)] h-[calc(100vh-60px)]
    overflow-hidden bg-gray-100" >
  <h1 class="text-3xl font-bold mb-6">DOCUMENT REQUEST</h1>

  <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="p-4 rounded-lg shadow-md" style="background-color: #EBF0F4;">
        <p class="text-3xl font-bold text-black" x-text="stats.registeredResidents">0</p>
        <p class="text-medium font-semibold text-black mt-1">TOTAL REQUEST</p>
    </div>

    <div class="p-4 rounded-lg shadow-md" style="background-color: #EBF0F4;">
        <p class="text-3xl font-bold text-black" x-text="stats.totalMale">0</p>
        <p class="text-medium font-semibold text-black mt-1">PENDING</p>
    </div>

    <div class="p-4 rounded-lg shadow-md" style="background-color: #EBF0F4;">
        <p class="text-3xl font-bold text-black" x-text="stats.totalFemale">0</p>
        <p class="text-medium font-semibold text-black mt-1">IN PROGRESS</p>
    </div>

    <div class="p-4 rounded-lg shadow-md" style="background-color: #EBF0F4;">
        <p class="text-3xl font-bold text-black" x-text="stats.totalArchived">0</p>
        <p class="text-medium font-semibold text-black mt-1">COMPLETED</p>
    </div>
</div>


  <div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-4">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00000080]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" placeholder="Search..." class="w-96 h-8 border border-gray-300 rounded-lg pl-10 pr-3 text-sm focus:outline-none" />
      </div>

<div class="flex gap-2 w-fit">
  <select class="h-8 px-3 text-sm focus:outline-none w-55">
    <option>DOCUMENT TYPE</option>
    <option>Barangay Clearance</option>
    <option>Barangay Certificate</option>
    <option>Indigency Clearance</option>
    <option>Resident Certificate</option>
  </select>

  <select class="h-8 px-3 text-sm focus:outline-none w-50">
    <option>STATUS</option>
    <option>Pending</option>
    <option>In Progress</option>
    <option>Completed</option>
  </select>
</div>


    </div>
</div>

<!-- TABLE-->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
  <table class="table table-zebra w-full text-md">

    <thead style="background-color: #134573; color: white;">
      <tr class="text-sm text-center whitespace-nowrap">
        <th class="py-1.5 px-4">TRANSACTION NO.</th>
        <th class="py-1.5 px-4">LAST NAME</th>
        <th class="py-1.5 px-4">FIRST NAME</th>
        <th class="py-1.5 px-4">DOCUMENT TYPE</th>
        <th class="py-1.5 px-4">DATE FILED</th>
        <th class="py-1.5 px-4">DATE COMPLETED</th>
        <th class="py-1.5 px-4">STATUS</th>
        <th class="py-1.5 px-4">ACTION</th>
      </tr>
    </thead>
    <tbody>

      <!-- ROW 1 -->
      <tr class="text-sm text-center whitespace-nowrap hover">
        <td class="py-2 px-5 border-b border-gray-200"></td>
        <td class="py-2 px-5 border-b border-gray-200"></td>
        <td class="py-2 px-5 border-b border-gray-200"></td>
        <td class="py-2 px-5 border-b border-gray-200"></td>
        <td class="py-2 px-5 border-b border-gray-200"></td>
        <td class="py-2 px-5 border-b border-gray-200"></td>

        <td class="py-2 px-5 border-b border-gray-200">
          <div class="flex justify-start items-center gap-2 ml-4">
            <!-- Icons -->
          </div>
        </td>
      </tr>

     </table>
</div>




    <div class="flex justify-end mt-4 gap-1">
      <a href="http://127.0.0.1:8000/superadmin/documents">
    <button class="px-3x w-10 bg-[#A2C4D9C7] text-black font-semibold text-md rounded-lg hover:bg-[#C1D2E1]">
          << </a>
    </button>
      <a href="http://127.0.0.1:8000/superadmin/documents/nextpage">
    <button class="px-3x w-10 bg-[#A2C4D9C7] text-black font-semibold text-md rounded-lg hover:bg-[#C1D2E1]">
       >> </a>
    </button>
</div>



</main>


@endsection

