import { Head } from '@inertiajs/react';

import AppearanceTabs from '@/tailwind/components/appearance-tabs';
import HeadingSmall from '@/tailwind/components/heading-small';
import { type BreadcrumbItem } from '@/tailwind/types';

import { edit as editAppearance } from '@/routes/appearance';
import AppLayout from '@/tailwind/layouts/app-layout';
import SettingsLayout from '@/tailwind/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: editAppearance().url,
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Appearance settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Appearance settings"
                        description="Update your account's appearance settings"
                    />
                    <AppearanceTabs />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
