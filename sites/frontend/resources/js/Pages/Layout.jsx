import React from "react";
import { Link, usePage } from '@inertiajs/react';

const Layout = ({ children }) => {
  const { component } = usePage();

  // Steps in the order they should appear
  // `componentName` must match the Inertia component name exactly
  const steps = [
    { label: 'Couch Quote', componentName: 'Home' },
    { label: 'Your Details', componentName: 'CustomerDetails' },
    { label: 'Make Payment', componentName: 'Checkout' },
    { label: 'Confirmation', componentName: 'Confirmation' },
  ];

  // Find which step index is currently active
  const currentStepIndex = steps.findIndex(
    (step) => step.componentName === component
  );

  // Renders the top step navigation
  const renderSteps = () => {
    return (
      <div className="flex justify-center space-x-8">
        {steps.map((step, index) => {
          // If index < currentStepIndex => step is completed
          // If index === currentStepIndex => step is active
          // If index > currentStepIndex => step is future
          const isCompleted = index < currentStepIndex;
          const isActive = index === currentStepIndex;

          return (
            <div key={step.label} className="flex flex-col items-center text-center">
              {/* Circle with number or checkmark */}
              <div
                className={`
                  w-8 h-8 rounded-full border-2 
                  flex items-center justify-center
                  ${isCompleted
                    ? 'bg-orange-500 border-orange-500 text-white'   // completed => orange circle + checkmark
                    : isActive
                      ? 'border-orange-500 text-orange-500'            // active => orange border, white background
                      : 'border-gray-300 text-gray-500'                // future => gray border
                  }
                `}
              >
                {isCompleted ? (
                  // Checkmark SVG for completed steps
                  <svg
                    className="w-4 h-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="3"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <path d="M20 6L9 17l-5-5" />
                  </svg>
                ) : (
                  // Step number for active & future steps
                  index + 1
                )}
              </div>

              {/* Step label underneath */}
              <span
                className={`mt-2 ${isActive || isCompleted ? 'text-orange-500 font-semibold' : 'text-gray-500'
                  }`}
              >
                {step.label}
              </span>
            </div>
          );
        })}
      </div>
    );
  };

  return (
    <div className="flex flex-col min-h-screen bg-gray-100">
      {/* Step Wizard Header */}
      <header className="bg-white shadow-md p-6">{renderSteps()}</header>

      {/* Main Content */}
      <main className="flex-grow container mx-auto p-6">{children}</main>

      {/* Footer */}
      <footer className="bg-white shadow-md p-4 text-center">
        <p className="text-gray-600">&copy; {new Date().getFullYear()} Laravel Inertia App</p>
      </footer>
    </div>
  );
};

export default Layout;