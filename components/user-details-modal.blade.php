@auth
<div id="user-details-modal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm flex items-center justify-center">
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 mx-2">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">My Account</h2>
      <button id="user-details-modal-close" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-2xl">&times;</button>
    </div>

    <div class="space-y-3">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
            <p class="text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
            <p class="text-gray-900 dark:text-gray-100">{{ Auth::user()->email }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">User Type</p>
            <p class="text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', Auth::user()->type)) }}</p>
        </div>
    </div>

    <div class="mt-6 text-right">
        <button id="user-details-modal-close-button" type="button" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
            Close
        </button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userDetailsModal = document.getElementById('user-details-modal');
    const closeButtonInsideModal = document.getElementById('user-details-modal-close-button');

    if (closeButtonInsideModal && userDetailsModal) {
        closeButtonInsideModal.addEventListener('click', () => {
            userDetailsModal.classList.add('hidden');
        });
    }
});
</script>
@endauth
