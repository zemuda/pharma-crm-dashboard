import AppLayout from '@/tailwind/layouts/app-layout';
import { type BreadcrumbItem } from '@/tailwind/types';
import { Input } from '@/tiptap_editor/components/ui/input';
import { Label } from '@/tiptap_editor/components/ui/label';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'List Contacts',
        href: '/crm/contacts',
    },
];

export default function Index() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List Contacts" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="grid gap-2">
                        <Label htmlFor="current_password">
                            Current password
                        </Label>

                        <Input
                            id="current_password"
                            // ref={currentPasswordInput}
                            name="current_password"
                            type="password"
                            className="mt-1 block w-full"
                            autoComplete="current-password"
                            placeholder="Current password"
                        />

                        {/* <InputError
                                        message={errors.current_password}
                                    /> */}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
