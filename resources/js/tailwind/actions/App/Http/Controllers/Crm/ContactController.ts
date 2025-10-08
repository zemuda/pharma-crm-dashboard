import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/crm/contacts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Crm\ContactController::index
 * @see app/Http/Controllers/Crm/ContactController.php:15
 * @route '/crm/contacts'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/crm/contacts/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Crm\ContactController::create
 * @see app/Http/Controllers/Crm/ContactController.php:23
 * @route '/crm/contacts/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Crm\ContactController::store
 * @see app/Http/Controllers/Crm/ContactController.php:31
 * @route '/crm/contacts'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/crm/contacts',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::store
 * @see app/Http/Controllers/Crm/ContactController.php:31
 * @route '/crm/contacts'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::store
 * @see app/Http/Controllers/Crm/ContactController.php:31
 * @route '/crm/contacts'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::store
 * @see app/Http/Controllers/Crm/ContactController.php:31
 * @route '/crm/contacts'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::store
 * @see app/Http/Controllers/Crm/ContactController.php:31
 * @route '/crm/contacts'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
export const show = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/crm/contacts/{contact}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
show.url = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { contact: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { contact: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    contact: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        contact: typeof args.contact === 'object'
                ? args.contact.id
                : args.contact,
                }

    return show.definition.url
            .replace('{contact}', parsedArgs.contact.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
show.get = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
show.head = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
    const showForm = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
        showForm.get = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Crm\ContactController::show
 * @see app/Http/Controllers/Crm/ContactController.php:39
 * @route '/crm/contacts/{contact}'
 */
        showForm.head = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
export const edit = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/crm/contacts/{contact}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
edit.url = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { contact: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { contact: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    contact: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        contact: typeof args.contact === 'object'
                ? args.contact.id
                : args.contact,
                }

    return edit.definition.url
            .replace('{contact}', parsedArgs.contact.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
edit.get = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
edit.head = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
    const editForm = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
        editForm.get = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Crm\ContactController::edit
 * @see app/Http/Controllers/Crm/ContactController.php:47
 * @route '/crm/contacts/{contact}/edit'
 */
        editForm.head = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
export const update = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/crm/contacts/{contact}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
update.url = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { contact: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { contact: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    contact: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        contact: typeof args.contact === 'object'
                ? args.contact.id
                : args.contact,
                }

    return update.definition.url
            .replace('{contact}', parsedArgs.contact.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
update.put = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
update.patch = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
    const updateForm = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
        updateForm.put = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Crm\ContactController::update
 * @see app/Http/Controllers/Crm/ContactController.php:55
 * @route '/crm/contacts/{contact}'
 */
        updateForm.patch = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Crm\ContactController::destroy
 * @see app/Http/Controllers/Crm/ContactController.php:63
 * @route '/crm/contacts/{contact}'
 */
export const destroy = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/crm/contacts/{contact}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Crm\ContactController::destroy
 * @see app/Http/Controllers/Crm/ContactController.php:63
 * @route '/crm/contacts/{contact}'
 */
destroy.url = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { contact: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { contact: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    contact: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        contact: typeof args.contact === 'object'
                ? args.contact.id
                : args.contact,
                }

    return destroy.definition.url
            .replace('{contact}', parsedArgs.contact.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Crm\ContactController::destroy
 * @see app/Http/Controllers/Crm/ContactController.php:63
 * @route '/crm/contacts/{contact}'
 */
destroy.delete = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Crm\ContactController::destroy
 * @see app/Http/Controllers/Crm/ContactController.php:63
 * @route '/crm/contacts/{contact}'
 */
    const destroyForm = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Crm\ContactController::destroy
 * @see app/Http/Controllers/Crm/ContactController.php:63
 * @route '/crm/contacts/{contact}'
 */
        destroyForm.delete = (args: { contact: string | number | { id: string | number } } | [contact: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const ContactController = { index, create, store, show, edit, update, destroy }

export default ContactController