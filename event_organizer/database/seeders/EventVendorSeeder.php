<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventVendorSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('event_vendor')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $events = DB::table('events')->get();

        foreach ($events as $event) {
            $templatePackageId = $event->package_id ?: 3;

            $bridalVendorId = DB::table('package_vendor_pivot')
                ->where('package_id', $templatePackageId)
                ->where('vendor_category_id', 3)
                ->inRandomOrder()->value('vendor_id')
                ?? DB::table('category_vendor')->where('category_id', 3)->inRandomOrder()->value('vendor_id');

            $docVendorId = DB::table('package_vendor_pivot')
                ->where('package_id', $templatePackageId)
                ->where('vendor_category_id', 6)
                ->inRandomOrder()->value('vendor_id')
                ?? DB::table('category_vendor')->where('category_id', 6)->inRandomOrder()->value('vendor_id');

            $suitVendorId = DB::table('package_vendor_pivot')
                ->where('package_id', $templatePackageId)
                ->where('vendor_category_id', 4)
                ->inRandomOrder()->value('vendor_id')
                ?? DB::table('category_vendor')->where('category_id', 4)->inRandomOrder()->value('vendor_id');

            $decorVendorId = DB::table('package_vendor_pivot')
                ->where('package_id', $templatePackageId)
                ->where('vendor_category_id', 30)
                ->inRandomOrder()->value('vendor_id')
                ?? DB::table('category_vendor')->where('category_id', 30)->inRandomOrder()->value('vendor_id');

            $templates = DB::table('package_templates')->where('package_id', $templatePackageId)->get();
            $eventVendors = [];
            $chargedVendors = [];

            foreach ($templates as $template) {
                $vendorId = null;
                $contactId = null;
                $vendorPkgId = null;
                $dealPrice = 0;
                $netPrice = 0;
                $status = 'unassigned';
                $picName = null;
                $picPhone = null;

                $isIncluded = $event->package_id ? $template->is_included : false;

                $shouldAssign = false;
                if (in_array($event->status, ['completed', 'ongoing'])) {
                    $shouldAssign = true;
                    $status = 'verified';
                } elseif ($event->status === 'planning') {
                    $shouldAssign = (rand(1, 10) <= 8);
                    $status = $shouldAssign ? 'verified' : 'reviewing';
                }

                if ($shouldAssign) {
                    $catId = $template->vendor_category_id;

                    if (in_array($catId, [14, 15])) {
                        $vendorId = 71;
                    } elseif (in_array($catId, [30, 31])) {
                        $vendorId = $decorVendorId;
                    } elseif (in_array($catId, [2, 3, 9, 10, 11])) {
                        $vendorId = $bridalVendorId;
                    } elseif (in_array($catId, [5, 6])) {
                        $vendorId = $docVendorId;
                    } elseif (in_array($catId, [4, 12])) {
                        $vendorId = $suitVendorId;
                    } else {
                        $vendorPivot = DB::table('package_vendor_pivot')
                            ->where('package_id', $templatePackageId)
                            ->where('vendor_category_id', $catId)
                            ->inRandomOrder()
                            ->first();

                        if (!$vendorPivot) {
                            $vendorPivot = DB::table('category_vendor')
                                ->where('category_id', $catId)
                                ->inRandomOrder()
                                ->first();
                        }
                        $vendorId = $vendorPivot ? $vendorPivot->vendor_id : null;
                    }

                    if ($vendorId) {
                        $contact = DB::table('vendor_contacts')->where('vendor_id', $vendorId)->first();
                        if ($contact) {
                            $contactId = $contact->id;
                            $picName = $contact->name;
                            $picPhone = $contact->phone;
                        }

                        $vPackage = DB::table('vendor_packages')
                            ->where('vendor_id', $vendorId)
                            ->where('vendor_category_id', $catId)
                            ->inRandomOrder()
                            ->first();

                        if (!$vPackage) {
                            $vPackage = DB::table('vendor_packages')->where('vendor_id', $vendorId)->inRandomOrder()->first();
                        }

                        if ($vPackage) {
                            $vendorPkgId = $vPackage->id;

                            if (!in_array($vendorId, $chargedVendors)) {
                                $publishPrice = $vPackage->price;

                                $netPrice = $publishPrice - 100000;

                                $dealPrice = $isIncluded ? 0 : $publishPrice;

                                $chargedVendors[] = $vendorId;
                            } else {
                                $netPrice = 0;
                                $dealPrice = 0;
                            }
                        }
                    } else {
                        $status = 'unassigned';
                    }
                }

                $eventVendors[] = [
                    'event_id'           => $event->id,
                    'vendor_category_id' => $template->vendor_category_id,
                    'vendor_id'          => $vendorId,
                    'vendor_contact_id'  => $contactId,
                    'vendor_package_id'  => $vendorPkgId,
                    'session'            => $template->session,
                    'role_detail'        => '-',
                    'is_included'        => $isIncluded,
                    'deal_price'         => $dealPrice,
                    'net_price'          => $netPrice,
                    'meal_crew'          => $vendorId ? rand(2, 8) : 0,
                    'pic_name'           => $picName,
                    'pic_phone'          => $picPhone,
                    'status'             => $status,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }

            DB::table('event_vendor')->insert($eventVendors);
        }
    }
}
