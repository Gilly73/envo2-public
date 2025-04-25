import React from 'react';

const Dropdown = ({ label, name, value, onChange, options, error, disabled = false }) => (
  <div>
    <label className="block text-sm font-medium text-gray-700">
      {label}*
    </label>
    <select
      name={name}
      value={value}
      onChange={onChange}
      disabled={disabled}
      className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500"
    >
      <option value="">{`Select ${label}`}</option>
      {options.map((option) => (
        <option key={option.value} value={option.value}>
          {option.label}
        </option>
      ))}
    </select>
    {error && <div className="text-red-500">{error}</div>}
  </div>
);

export default Dropdown;