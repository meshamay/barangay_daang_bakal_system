@extends('admin.layouts.app')


@section('content')


<main class="fflex-1 p-11 fixed top-[60px] left-[220px]
    w-[calc(100vw-200px)] h-[calc(100vh-60px)]
    overflow-hidden bg-gray-100" >
  <h1 class="text-3xl font-bold mb-6">AUDIT LOGS</h1>

<div class="flex items-center gap-6">

    {{-- Date Filters --}}
    <div class="flex items-center gap-3 bg-gray-100 p-3 rounded-xl shadow-sm w-fit">

        <!-- FROM -->
        <div class="flex flex-col">
            <label class="text-xs text-gray-500 mb-1">From</label>
            <input
                type="date"
                id="fromDate"
                class="h-8 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#134573] focus:outline-none"
            >
        </div>

        <!-- Divider -->
        <span class="text-gray-400 mt-6">—</span>

        <!-- TO -->
        <div class="flex flex-col">
            <label class="text-xs text-gray-500 mb-1">To</label>
            <input
                type="date"
                id="toDate"
                class="h-8 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#134573] focus:outline-none"
            >
        </div>

        <!-- APPLY BUTTON -->
        <button
            id="applyFilter"
            class="h-8 mt-5 bg-[#134573] flex items-center text-white px-4 py-2 rounded-lg text-sm hover:bg-[#C1D2E1] transition">
            Apply
        </button>
    </div>

    {{-- Role Filter --}}
     <select class="h-8 px-3 text-sm focus:outline-none w-fit mt-6">
        <option selected>ROLE</option>
        <option>User</option>
        <option>Admin</option>
        <option>Superadmin</option>
    </select>

</div>



{{-- Data Table --}}
<div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
  <table class="table table-zebra w-full text-md ">
             <thead style="background-color: #134573; color: white; text-center;">
                <tr class="text-sm whitespace-nowrap">
                    <th class="py-2 px-5 border-b border-gray-200">USER ID</th>
                    <th class="py-2 px-5 border-b border-gray-200">LAST NAME</th>
                    <th class="py-2 px-5 border-b border-gray-200">FIRST NAME</th>
                    <th class="py-2 px-5 border-b border-gray-200">ROLE</th>
                    <th class="py-2 px-5 border-b border-gray-200">DATE</th>
                    <th class="py-2 px-5 border-b border-gray-200">TIME</th>
                    <th class="py-2 px-5 border-b border-gray-200">ACTION PERFORMED</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-sm whitespace-nowrap text-center">
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"></td>
                    <td class="py-2 px-5 border-b border-gray-200"><td>
                </tr>
            </tbody>
        </table>
</div>

     {{-- Pagination --}}

        <div class="flex justify-end mt-4 gap-1">
            <a href="http://127.0.0.1:8000/superadmin/auditlog">
            <button class="px-3x w-10 bg-[#A2C4D9C7] text-black font-semibold text-md rounded-lg hover:bg-[#C1D2E1]">
                << </a>
            </button>
            <a href="http://127.0.0.1:8000/superadmin/auditlog/nextpage">
            <button class="px-3x w-10 bg-[#A2C4D9C7] text-black font-semibold text-md rounded-lg hover:bg-[#C1D2E1]">
            >> </a>
            </button>
        </div>



    </main>

@endsection
