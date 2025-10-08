import { AppContent } from '@/tailwind/components/app-content';
import { AppShell } from '@/tailwind/components/app-shell';
import { AppSidebar } from '@/tailwind/components/app-sidebar';
import { AppSidebarHeader } from '@/tailwind/components/app-sidebar-header';
import { type BreadcrumbItem } from '@/tailwind/types';
import { type PropsWithChildren } from 'react';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                {children}
            </AppContent>
        </AppShell>
    );
}
