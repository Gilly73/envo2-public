// InputField.jsx
import React from 'react';

const InputField = ({ label, name, type = 'text', error, ...rest }) => {
  return (
    <div>
      <label className="block text-sm font-medium text-gray-700">
        {label}
      </label>
      <input
        type={type}
        name={name}
        className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500"
        {...rest}
      />
      {error && <div className="text-red-500">{error}</div>}
    </div>
  );
};

export default InputField;