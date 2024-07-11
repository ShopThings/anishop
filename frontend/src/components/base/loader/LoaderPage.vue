<template>
  <div class="p-4 w-full mx-auto">
    <div class="animate-pulse flex flex-col">
      <div class="p-4 rounded-lg mb-6 flex gap-3 items-center">
        <div class="h-3 bg-slate-300 rounded w-48"></div>
        <div class="h-3 bg-slate-300 rounded w-16"></div>
      </div>

      <div class="bg-white p-4 rounded-lg mb-3 flex flex-col gap-6">

        <div class="flex flex-col gap-3">
          <div class="h-2 bg-cyan-200 rounded w-full"></div>
          <div class="h-2 bg-cyan-200 rounded w-full"></div>
          <div class="h-2 bg-cyan-200 rounded w-1/2"></div>
        </div>

        <div class="flex flex-col gap-3">
          <div class="h-2 bg-slate-200 rounded w-full"></div>
          <div class="h-2 bg-slate-200 rounded w-full"></div>
          <div class="h-2 bg-slate-200 rounded w-2/3"></div>
          <div class="h-2 bg-slate-200 rounded w-1/4"></div>
          <div class="h-2 bg-slate-200 rounded w-1/6"></div>
          <div class="h-2 bg-slate-200 rounded w-1/2"></div>
        </div>

        <div class="flex flex-col gap-3">
          <div class="h-2 bg-slate-200 rounded w-full"></div>
          <div class="h-2 bg-slate-200 rounded w-2/3"></div>
          <div class="h-2 bg-slate-200 rounded w-1/6"></div>
          <div class="h-2 bg-slate-200 rounded w-full"></div>
          <div class="h-2 bg-slate-200 rounded w-1/4"></div>
          <div class="h-2 bg-slate-200 rounded w-1/2"></div>
        </div>

        <div class="flex flex-wrap items-center">
          <div class="p-3 w-full sm:w-1/2 lg:w-1/4">
            <div class="flex items-center justify-center h-44 bg-slate-200 rounded mx-auto">
              <svg aria-hidden="true" class="w-10 h-10 text-slate-200 dark:text-slate-600"
                   fill="currentColor" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
              </svg>
            </div>
          </div>
          <div class="p-3 w-full sm:w-1/2 lg:w-1/4">
            <div class="flex items-center justify-center h-44 bg-slate-200 rounded mx-auto">
              <svg aria-hidden="true" class="w-10 h-10 text-slate-200 dark:text-slate-600"
                   fill="currentColor" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
              </svg>
            </div>
          </div>
          <div class="p-3 w-full sm:w-1/2 lg:w-1/4">
            <div class="flex items-center justify-center h-44 bg-slate-200 rounded mx-auto">
              <svg aria-hidden="true" class="w-10 h-10 text-slate-200 dark:text-slate-600"
                   fill="currentColor" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
              </svg>
            </div>
          </div>
          <div class="p-3 w-full sm:w-1/2 lg:w-1/4">
            <div class="flex items-center justify-center h-44 bg-slate-200 rounded mx-auto">
              <svg aria-hidden="true" class="w-10 h-10 text-slate-200 dark:text-slate-600"
                   fill="currentColor" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
</script>
