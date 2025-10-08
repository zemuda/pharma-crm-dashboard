import { AppContent } from '@/tailwind/components/app-content';
import { AppHeader } from '@/tailwind/components/app-header';
import { AppShell } from '@/tailwind/components/app-shell';
import { type BreadcrumbItem } from '@/tailwind/types';
import type { PropsWithChildren } from 'react';

export default function AppHeaderLayout({
    children,
    breadcrumbs,
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    return (
        <AppShell>
            <AppHeader breadcrumbs={breadcrumbs} />
            <AppContent>{children}</AppContent>
        </AppShell>
    );
}
