import { type BreadcrumbItem } from '@/tailwind/types';
import { AppContent } from '@/tiptap_editor/components/app-content';
import { AppShell } from '@/tiptap_editor/components/app-shell';
import { AppSidebar } from '@/tiptap_editor/components/app-sidebar';
import { AppSidebarHeader } from '@/tiptap_editor/components/app-sidebar-header';
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
