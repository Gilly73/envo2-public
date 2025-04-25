import React from 'react';

export default function FormButtons({
  onBack,
  onReset,
  submitLabel = 'Next',
  backLabel = 'Back',
  resetLabel = 'Reset',
  showBack = true,
  showSubmit = true,
  showReset = true,
}) {
  return (
    <div className="flex space-x-2 pb-5">
      {showBack && (
        <button
          type="button"
          onClick={onBack}
          className="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300"
        >
          {backLabel}
        </button>
      )}
      {showSubmit && (
        <button
          type="submit"
          className="bg-orange-500 text-white px-4 py-2 rounded-md shadow hover:bg-orange-600"
        >
          {submitLabel}
        </button>
      )}
      {showReset && (
        <button
          type="button"
          onClick={onReset}
          className="bg-red-500 text-white px-4 py-2 rounded-md shadow hover:bg-red-600"
        >
          {resetLabel}
        </button>
      )}
    </div>
  );
}
