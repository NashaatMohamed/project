<?php
    /*
    |--------------------------------------------------------------------------
    | Líneas de idioma de aplicación
	|	Traducido al español por @EnigmaHernandez In Telegram
	|   https://github.com/enigmaelectronica/-Foxtrot-SaaS
    |--------------------------------------------------------------------------
    */
return [

	 /*
     | --------------------------------------------------------------------------
     | Líneas de idioma de validación
     | --------------------------------------------------------------------------
     |
     | Las siguientes líneas de idioma contienen los mensajes de error predeterminados utilizados por
     | la clase de validador. Algunas de estas reglas tienen múltiples versiones como
     | como las reglas de tamaño. Siéntase libre de modificar cada uno de estos mensajes aquí.
     |
     */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'active_url' => 'El campo :attribute No es una dirección URL válida.',
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => 'El campo :attribute solo puede contener letras.',
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos..',
    'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
    'array' => 'El campo :attribute debe ser un arreglo o matriz.',
    'before' => 'El campo :attribute debe ser una fecha antes de :date.',
    'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file' => 'El campo :attribute debe estar entre :min y :max kilobytes.',
        'string' => 'El campo :attribute debe estar entre :min y :max characters.',
        'array' => 'El campo :attribute debe estar entre :min y :max items.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'El campo :attribute de confirmación no coincide .',
    'date' => 'El campo :attribute no es una fecha válida.',
    'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El campo :attribute no coincide con el formato :format.',
    'different' => 'El campo :attribute y :other deben ser diferentes.',
    'digits' => 'El campo :attribute debe ser :digits digits.',
    'digits_between' => 'El campo :attributedebe estar entre :min y :max digitos.',
    'dimensions' => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct' => 'El campo :attribute el campo tiene un valor duplicado.',
    'email' => 'El campo :attribute Debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El campo :attribute debe terminar con uno de los siguientes: :values.',
    'exists' => 'El campo seleccionado :attribute es inválido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener o ser un valor.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor que :value caracteres.',
        'array' => 'El campo :attribute debe tener más de :value items.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'file' => 'El campo :attribute debe ser mayor o igual que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor o igual que :value caracteres.',
        'array' => 'El campo :attribute debe tener :value items o más.',
    ],
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El campo seleccionado :attribute es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El campo :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file' => 'El campo :attributedebe ser menor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor que :value characters.',
        'array' => 'El campo :attribute debe tener menos de :value items.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
        'file' => 'El campo :attribute debe ser menor o igual que :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor o igual que :value characters.',
        'array' => 'El campo :attribute no debe tener más de :value items.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute puede no ser mayor que :max.',
        'file' => 'El campo :attribute puede no ser mayor que :max kilobytes.',
        'string' => 'El campo :attribute puede no ser mayor que :max caracteres.',
        'array' => 'El campo :attribute puede no tener más de :max items.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
        'file' => 'El campo :attribute debe ser como mínimo :min kilobytes.',
        'string' => 'El campo :attribute debe ser como mínimo :min caracteres.',
        'array' => 'El campo :attribute debe ser como mínimo :min items.',
    ],
    'not_in' => 'El campo selected :attribute es invalido.',
    'not_regex' => 'El campo :attribute format es invalido.',
    'numeric' => 'El campo :attribute Tiene que ser un número.',
    'password' => 'El campo password es incorrecto.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El campo :attribute el formato no es válido.',
    'required' => 'El campo :attribute es requerido.',
    'required_if' => 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El campo :attribute es obligatorio a menos que :other es en :values.',
    'required_with' => 'El campo :attribute es requeridp cuando :values esta presente.',
    'required_with_all' => 'El campo :attribute es requerido cuando :values estan presentes.',
    'required_without' => 'El campo :attribute es requerdo cuando :values no este presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values estan presentes.',
    'same' => 'El campo :attribute y :other deben coincidir.',
    'match_with_old_password' => 'El campo :attribute debe coincidir con su contraseña anterior.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file' => 'El campo :attribute debe ser :size kilobytes.',
        'string' => 'El campo :attribute debe ser :size characters.',
        'array' => 'El campo :attribute debe contener :size items.',
    ],
    'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes: :values.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'timezone' => 'El campo :attribute debe ser una zona válida.',
    'unique' => 'El campo :attribute ya se ha tomado.',
    'uploaded' => 'El archivo :attribute no se pudo cargar.',
    'url' => 'El campo :attribute es inválido.',
    'uuid' => 'El campo :attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Líneas de idioma de validación personalizadas
    |--------------------------------------------------------------------------
    |
    | Aquí puede especificar mensajes de validación personalizados para atributos usando el
    | convención "attribute.rule" para nombrar las líneas. Esto hace que sea rápido
    | especificar una línea de idioma personalizada específica para una regla de atributo determinada.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atributos de validación personalizados
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas de idioma se utilizan para intercambiar nuestro marcador de posición de atributo
    | con algo más fácil de leer, como "Dirección de correo electrónico" en su lugar
    | de "correo electrónico". Esto simplemente nos ayuda a hacer que nuestro mensaje sea más expresivo.
    |
    */

    'attributes' => [],

];
