// import { Inertia } from "@inertiajs/inertia";
import React, { useEffect, useState } from 'react';

const Navdata = () => {
    //state data
    const [isDashboard, setIsDashboard] = useState<boolean>(false);
    const [isInventory, setIsInventory] = useState<boolean>(false);

    // Inventory
    const [isProducts, setIsProducts] = useState<boolean>(false);
    const [isMedicines, setIsMedicines] = useState<boolean>(false);
    const [isTherapeuticClasses, setIsTherapeuticClasses] =
        useState<boolean>(false);

    const [iscurrentState, setIscurrentState] = useState('Dashboard');

    function updateIconSidebar(e: any) {
        if (e && e.target && e.target.getAttribute('sub-items')) {
            const ul: any = document.getElementById('two-column-menu');
            const iconItems: any = ul.querySelectorAll('.nav-icon.active');
            let activeIconItems = [...iconItems];
            activeIconItems.forEach((item) => {
                item.classList.remove('active');
                var id = item.getAttribute('sub-items');
                const getID: any = document.getElementById(id) as HTMLElement;
                if (getID) getID?.parentElement.classList.remove('show');
            });
        }
    }

    useEffect(() => {
        document.body.classList.remove('twocolumn-panel');
        if (iscurrentState !== 'Dashboard') {
            setIsDashboard(false);
        }
        if (iscurrentState !== 'Inventory') {
            setIsInventory(false);
        }
    }, [history, iscurrentState, isInventory]);

    const menuItems: any = [
        {
            label: 'Menu',
            isHeader: true,
        },
        {
            label: 'Inventory Management',
            isHeader: true,
        },
        {
            id: 'inventory',
            label: 'Inventory',
            icon: 'ri-store-2-line',
            link: '/#',
            click: function (e: any) {
                e.preventDefault();
                setIsInventory(!isInventory);
                setIscurrentState('Inventory');
                updateIconSidebar(e);
            },
            stateVariables: isInventory,
            subItems: [
                {
                    id: 'product',
                    label: 'Products',
                    link: '/#',
                    parentId: 'inventory',
                    isChildItem: true,
                    click: function (e: any) {
                        e.preventDefault();
                        setIsProducts(!isProducts);
                    },
                    stateVariables: isProducts,
                    childItems: [
                        {
                            id: 1,
                            label: 'List Products',
                            link: '/ims/products',
                            parentId: 'inventory',
                        },
                        // {
                        //     id: 2,
                        //     label: 'Job Lists',
                        //     link: '/#',
                        //     parentId: 'apps',
                        //     isChildItem: true,
                        //     stateVariables: isJobList,
                        //     click: function (e: any) {
                        //         e.preventDefault();
                        //         setIsJobList(!isJobList);
                        //     },
                        //     childItems: [
                        //         {
                        //             id: 1,
                        //             label: 'List',
                        //             link: '/apps-job-lists',
                        //             parentId: 'apps',
                        //         },
                        //         {
                        //             id: 2,
                        //             label: 'Grid',
                        //             link: '/apps-job-grid-lists',
                        //             parentId: 'apps',
                        //         },
                        //         {
                        //             id: 3,
                        //             label: 'Overview',
                        //             link: '/apps-job-details',
                        //             parentId: 'apps',
                        //         },
                        //     ],
                        // },
                    ],
                },
                {
                    id: 'medicine',
                    label: 'Medicines',
                    link: '/#',
                    parentId: 'inventory',
                    isChildItem: true,
                    click: function (e: any) {
                        e.preventDefault();
                        setIsMedicines(!isMedicines);
                    },
                    stateVariables: isMedicines,
                    childItems: [
                        {
                            id: 1,
                            label: 'List Medicines',
                            link: '/ims/medicines',
                            parentId: 'inventory',
                        },
                        {
                            id: 2,
                            label: 'Therapeutic Classes',
                            link: '/#',
                            parentId: 'inventory',
                            isChildItem: true,
                            stateVariables: isTherapeuticClasses,
                            click: function (e: any) {
                                e.preventDefault();
                                setIsTherapeuticClasses(!isTherapeuticClasses);
                            },
                            childItems: [
                                {
                                    id: 1,
                                    label: 'List Therapeutic Classes',
                                    link: '/ims/therapeutic-classes',
                                    parentId: 'inventory',
                                },
                                {
                                    id: 2,
                                    label: 'Create Therapeutic Class',
                                    link: '/ims/therapeutic-classes/create',
                                    parentId: 'inventory',
                                },
                                {
                                    id: 3,
                                    label: 'Overview',
                                    link: '/apps-job-details',
                                    parentId: 'apps',
                                },
                            ],
                        },
                    ],
                },
            ],
        },
    ];
    return <React.Fragment>{menuItems}</React.Fragment>;
};
export default Navdata;
