User profile with Country and PostalCode

- Remove geo in context model (remove column in DB)
- Check creation profile form
- Make country column NULLABLE and nullify empty-string valued
- Make postal_code column NULLABLE and nullify empty-string valued
- Value coordinates at Model update/create from country and postalCode
- Value country column, from coordinates ? Then remove wrong departments (country not FR)
