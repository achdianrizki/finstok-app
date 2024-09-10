<div class="mt-6">
  <div class="overflow-x-auto">
      <table class="min-w-full bg-white dark:bg-dark-eval-1 rounded-md">
          @if(isset($header))
              <thead>
                  <tr>
                      {{ $header }}
                  </tr>
              </thead>
          @endif
          <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
              {{ $slot }}
          </tbody>
      </table>
  </div>
</div>