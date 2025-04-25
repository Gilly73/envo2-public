import { useState, useRef } from 'react';
import { useMultiStepForm } from '../context/MultiStepFormContext.jsx'; // Import Context
import axios from 'axios';
import Layout from "./Layout";
import InputField from '../components/form/Inputfield';
import Country from '../components/form/InputDropdown';
import { router } from '@inertiajs/react';
import FormButtons from '../components/navigation/NavButtons.jsx';

const countryOptions = [
    { value: 'UK', label: 'UK' },
    { value: 'US', label: 'US' },
]

const CustomerDetails = () => {

    const { quote,
        customer,
        formData,
        updateFormData,
        resetFormData,
        fetchQuote,
        createCustomer } = useMultiStepForm();

    const [errors, setErrors] = useState({});
    const [country, setCountry] = useState(formData.country);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formElement = e.target;
        const submittedData = Object.fromEntries(new FormData(formElement).entries());

        // Merge customer details into the global form data
        updateFormData(submittedData);

        const customerData = {
            ...formData,
            ...submittedData,
            quote_description: quote?.description, // Add quote ID from context
            quote_totalCost: quote?.totalCost, // Add quote cost from context
        };

        try {
            await createCustomer(submittedData);
        } catch (error) {
            //setErrors(error.response?.data.errors || {});
            setErrors(error);
        }
    };


    return (
        <Layout>
            <div className="bg-white shadow-md rounded-lg p-6 w-full max-w-lg mx-auto">
                <h2 className="text-orange-500 text-2xl font-bold mb-4">
                    Your Details
                </h2>
                <FormButtons
                    showBack={true}
                    showSubmit={false}
                    showReset={false}
                    onBack={() => router.visit('/')}
                />
                <div className="p-4 bg-gray-100 rounded-md">
                    {quote && quote.totalCost ? (
                        <>
                            <p><strong>Quote Description:</strong> {quote.description}</p>
                            <p><strong>Quote Total Cost:</strong> £{quote.totalCost}</p>
                        </>

                    ) : (
                        <p className="text-gray-500">No quote available.</p> // Handle missing quote
                    )}
                </div>
                <form onSubmit={handleSubmit} className="space-y-4 p-4">
                    <InputField
                        label="First name*"
                        name="firstName"
                        defaultValue={formData.firstName || ''}
                        error={errors?.firstName && errors.firstName[0]}
                    />
                    <InputField
                        label="Surname*"
                        name="surname"
                        defaultValue={formData.surname || ''}
                        error={errors?.surname && errors.surname[0]}
                    />
                    <InputField
                        label="Date of Birth*"
                        name="dob"
                        type="date"
                        defaultValue={formData.dob || ''}
                        error={errors?.dob && errors.dob[0]}
                    />
                    <InputField
                        label="Email*"
                        name="email"
                        type="email"
                        defaultValue={formData.email || ''}
                        error={errors?.email && errors.email[0]}
                    />
                    <InputField
                        label="Mobile*"
                        name="mobile"
                        defaultValue={formData.mobile || ''}
                        error={errors?.mobile && errors.mobile[0]}
                    />
                    <InputField
                        label="Address Line 1*"
                        name="address1"
                        defaultValue={formData.address1 || ''}
                        error={errors?.address1 && errors.address1[0]}
                    />
                    <InputField
                        label="Address Line 2"
                        name="address2"
                        defaultValue={formData.address2 || ''}
                        error={errors?.address2 && errors.address2[0]}
                    />
                    <InputField
                        label="City*"
                        name="city"
                        defaultValue={formData.city || ''}
                        error={errors?.city && errors.city[0]}
                    />
                    <InputField
                        label="County*"
                        name="county"
                        defaultValue={formData.county || ''}
                        error={errors?.county && errors.county[0]}
                    />
                    <InputField
                        label="Postcode*"
                        name="postcode"
                        defaultValue={formData.postcode || ''}
                        error={errors?.postcode && errors.postcode[0]}
                    />

                    <Country
                        label="Country"
                        name="country"
                        value={country}
                        onChange={(e) => setCountry(e.target.value)}
                        options={countryOptions}
                        error={errors?.country && errors.country[0]}
                    />



                    {/* Submit Button */}
                    <div>
                        <button
                            id="customer"
                            type="submit"
                            className="w-full bg-orange-500 text-white px-4 py-2 rounded-md shadow hover:bg-orange-600"
                        >
                            Submit Customer
                        </button>
                    </div>





                </form>
            </div>
        </Layout>
    );
}
export default CustomerDetails;