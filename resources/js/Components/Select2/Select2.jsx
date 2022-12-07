import React, { useState } from 'react';
import AsyncSelect from 'react-select/async';

import './Select2.css'

export default function Select2({ value, defaultOptions, onChange, loadOptions }) {

    const _value = defaultOptions.find((option) => option.value === value )

    return (
        <AsyncSelect
            value={_value}
            defaultOptions={defaultOptions}
            loadOptions={loadOptions}
            onChange={onChange}
            classNamePrefix="Select2"
        />
    );
}