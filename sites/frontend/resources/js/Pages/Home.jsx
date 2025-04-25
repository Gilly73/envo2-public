import { useState } from 'react';
import { useMultiStepForm } from '../context/MultiStepFormContext.jsx';
import Layout from "./Layout";
import Dropdown from '../components/form/HomeDropdown';
import Country from '../components/form/InputDropdown';
import InputField from '../components/form/Inputfield';
import FormButtons from '../components/navigation/NavButtons.jsx';
import { router } from '@inertiajs/react';

// const couchOptions = {
//   indoor: {
//     fabrics: [
//       { value: 'velvet', label: 'Velvet' },
//       { value: 'leather', label: 'Leather' },
//     ],
//     legs: [
//       { value: 'wood', label: 'Wood' },
//       { value: 'metal', label: 'Metal' },
//     ],
//     seaters: [
//       { value: '2', label: '2' },
//       { value: '3', label: '3' },
//       { value: '4', label: '4' },
//       { value: '5', label: '5' },
//     ],
//   },
//   outdoor: {
//     fabrics: [
//       { value: 'canvas', label: 'Canvas' },
//       { value: 'sunbrella', label: 'Sunbrella' },
//     ],
//     legs: [
//       { value: 'plastic', label: 'Plastic' },
//       { value: 'metal', label: 'Metal' },
//       { value: 'tin', label: 'Tin' },
//     ],
//     seaters: [
//       { value: '2', label: '2' },
//       { value: '3', label: '3' },
//       { value: '4', label: '4' },
//     ],
//   },
// };

// const countryOptions = [
//   { value: 'UK', label: 'UK' },
//   { value: 'US', label: 'US' },
//   { value: 'DE', label: 'DE' },
// ]

const Home = () => {

  const {
    productOptions,
    quote, 
    customer, 
    formData, 
    updateFormData, 
    resetFormData, 
    fetchQuote, 
    createCustomer } = useMultiStepForm();

  const [errors, setErrors] = useState({});

  const [couchType, setCouchType] = useState(formData.couchtype);
  const [fabricType, setFabricType] = useState(formData.fabrictype);
  const [legType, setLegType] = useState(formData.legtype);
  const [seaterType, setSeaterType] = useState(formData.seatertype);
  const [styleType, setStyleType] = useState(formData.styletype);
  const [discount, setDiscount] = useState(formData.discount);
  const [country, setCountry] = useState(formData.country);

  const couchOptions = productOptions.couchOptions || {};
  const countryOptions = productOptions.countryOptions || [];
  const couchTypesOptions = productOptions.couchTypesOptions || [];
  
  const fabricOptions = couchType ? couchOptions[couchType].fabrics : [];
  const legOptions = couchType ? couchOptions[couchType].legs : [];
  const seaterOptions = couchType ? couchOptions[couchType].seaters : [];
  const styleOptions = couchType ? couchOptions[couchType].styles : [];

  const handleCouchTypeChange = (e) => {
    setCouchType(e.target.value);
    setFabricType('');
    setLegType('');
    setSeaterType('');
    setStyleType('');
    setDiscount('');
    setCountry('');
    setErrors('');
    updateFormData({ couchtype: e.target.value, fabrictype: '', legtype: '', seatertype: '', styletype: '', country: '', discount: '' });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const formElement = e.target;
    const formDataObj = Object.fromEntries(new FormData(formElement).entries());
    console.log('formDataObj', formDataObj);
    // Save Home form data into context
    updateFormData(formDataObj);

    try {
      // Fetch quote and store it globally
      await fetchQuote(new FormData(formElement));
    } catch (error) {
      setErrors(error); // Handle validation errors
    }

  };

  return (
    <Layout>
      <div className="bg-white shadow-md rounded-lg p-6 w-full max-w-lg mx-auto">
        <h2 className="text-orange-500 text-2xl font-bold mb-4">Couch Quote</h2>
        <FormButtons
          showBack={false}
          showSubmit={false}
          showReset={true}
          onReset={() => {
            resetFormData();
            router.visit('/');
          }}
        />
        <p>UK 50% discount UKSAVE50</p>
        <br />
        <form onSubmit={handleSubmit} className="space-y-4">
          <Dropdown
            label="Couch Type"
            name="couchtype"
            value={couchType}
            onChange={handleCouchTypeChange}
            options={couchTypesOptions}
            error={errors?.couchtype && errors.couchtype[0]}
          />

          <Dropdown
            label="Style Type"
            name="styletype"
            value={styleType}
            onChange={(e) => setStyleType(e.target.value)}
            options={styleOptions}
            error={errors?.styletype && errors.styletype[0]}
            disabled={!couchType}
          />

          <Dropdown
            label="Fabric Type"
            name="fabrictype"
            value={fabricType}
            onChange={(e) => setFabricType(e.target.value)}
            options={fabricOptions}
            error={errors?.fabrictype && errors.fabrictype[0]}
            disabled={!couchType}
          />

          <Dropdown
            label="Leg Type"
            name="legtype"
            value={legType}
            onChange={(e) => setLegType(e.target.value)}
            options={legOptions}
            error={errors?.legtype && errors.legtype[0]}
            disabled={!couchType}
          />

          <Dropdown
            label="Seater Type"
            name="seatertype"
            value={seaterType}
            onChange={(e) => setSeaterType(e.target.value)}
            options={seaterOptions}
            error={errors?.seatertype && errors.seatertype[0]}
            disabled={!couchType}
          />

          <InputField
            label="Discount*"
            name="discount"
            value={discount || ''}
            onChange={(e) => setDiscount(e.target.value)}
            error={errors?.discount && errors.discount[0]}
          />

          <Country
            label="Country"
            name="country"
            value={country}
            onChange={(e) => setCountry(e.target.label)}
            options={countryOptions}
            error={errors?.country && errors.country[0]}
          />

          <div>
            <button
              id="quote"
              type="submit"
              className="w-full bg-orange-500 text-white px-4 py-2 rounded-md shadow hover:bg-orange-600"
            >Get Quote
            </button>
          </div>
        </form>
      </div>
    </Layout>
  );
};

export default Home;