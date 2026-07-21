<x-admin-layout>
    <x-slot name="title">System Guide</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-[10px] font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('dashboard') }}" class="hover:text-accent">DASHBOARD</a>
            <span>/</span>
            <span class="text-text-primary">SYSTEM GUIDE</span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-10">
            <h2 class="text-4xl mb-1">SYSTEM GUIDE</h2>
            <p class="text-text-secondary font-mono text-[10px] uppercase tracking-widest">Step-by-step guide for first-time operators of the CCDI Tabulation System.</p>
            <button type="button" data-onboarding-start="admin" class="tactical-button mt-5">START INTERACTIVE TOUR</button>
        </div>

        <!-- Overview Banner -->
        <div class="tactical-card p-6 border-l-4 border-accent mb-8 bg-accent/5">
            <h3 class="text-xs font-mono text-accent uppercase tracking-widest mb-2">What is this system?</h3>
            <p class="text-[11px] font-mono text-text-secondary leading-relaxed">
                The CCDI Tabulation System is a digital scoring and tabulation platform used during pageants, competitions, and similar events.
                It allows an admin to set up contests, define scoring criteria, assign judges, and collect real-time scores â€” then automatically computes rankings and generates printable results.
            </p>
        </div>

        <!-- Step-by-step flow -->
        <div class="space-y-6">

            <!-- STEP 1 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">01</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">LOGIN AS ADMIN</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">URL: /admin/login</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Open the admin login page and enter your admin credentials. Only the administrator can access the back-end setup area.
                            Judges use a separate login at <span class="text-accent">/judge/login</span> using an access code â€” not the admin login.
                        </p>
                        <div class="bg-background-secondary/60 border border-border p-4 space-y-1">
                            <div class="text-[9px] font-mono text-text-secondary uppercase tracking-widest">Quick Info</div>
                            <div class="flex gap-8 text-[10px] font-mono mt-2">
                                <div><span class="text-accent">Admin URL: </span><span class="text-white">/admin/login</span></div>
                                <div><span class="text-accent">Judge URL: </span><span class="text-white">/judge/login</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">02</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">DEFINE SCORING CRITERIA</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Criteria</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Before creating a contest, set up your scoring criteria. Each criterion represents a category judges will score (e.g., <em>Talent</em>, <em>Beauty</em>, <em>Intelligence</em>) and has an assigned weight or maximum score.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">What to fill in</div>
                                <ul class="text-[10px] font-mono text-text-secondary space-y-1 list-disc list-inside">
                                    <li>Criterion name (e.g., Talent)</li>
                                    <li>Maximum score (e.g., 30)</li>
                                    <li>Description (optional)</li>
                                </ul>
                            </div>
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Important note</div>
                                <p class="text-[10px] font-mono text-text-secondary">All criteria scores combined should represent a total of 100 points for clean percentage-based tabulation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">03</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">CREATE A CONTEST</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Contests â†’ Create Contest</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Create your contest by filling in the basic details. The system will automatically generate judge accounts and contestant placeholders based on the numbers you specify.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-start gap-4 p-3 bg-background-secondary/60 border border-border">
                                <span class="text-accent font-mono text-[10px] uppercase shrink-0 w-32">Contest Name</span>
                                <span class="text-[10px] font-mono text-text-secondary">Official name of the event (e.g., "Mr. & Ms. CCDI 2025")</span>
                            </div>
                            <div class="flex items-start gap-4 p-3 bg-background-secondary/60 border border-border">
                                <span class="text-accent font-mono text-[10px] uppercase shrink-0 w-32">Contest Type</span>
                                <span class="text-[10px] font-mono text-text-secondary"><strong class="text-white">Double</strong> = Male + Female | <strong class="text-white">Single</strong> = one gender | <strong class="text-white">Group</strong> = team-based</span>
                            </div>
                            <div class="flex items-start gap-4 p-3 bg-background-secondary/60 border border-border">
                                <span class="text-accent font-mono text-[10px] uppercase shrink-0 w-32">No. of Judges</span>
                                <span class="text-[10px] font-mono text-text-secondary">Number of judges â€” each will receive a unique auto-generated access code</span>
                            </div>
                            <div class="flex items-start gap-4 p-3 bg-background-secondary/60 border border-border">
                                <span class="text-accent font-mono text-[10px] uppercase shrink-0 w-32">Contestants</span>
                                <span class="text-[10px] font-mono text-text-secondary">Number of male and/or female contestants â€” placeholders are auto-created for you to rename later</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">04</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">CONFIGURE CONTEST SETTINGS</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Contests â†’ [Contest Name] â†’ Settings</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            After creating the contest, you are automatically redirected to the Contest Settings page. Here you can update the contest details and upload a logo.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Logo Upload</div>
                                <p class="text-[10px] font-mono text-text-secondary">Click the logo preview area on the right side to select an image file. The preview updates immediately. Save changes to apply.</p>
                            </div>
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Status Field</div>
                                <p class="text-[10px] font-mono text-text-secondary">Keep status as <span class="text-warning">Pending</span> during setup. Set to <span class="text-success">Active</span> only when ready to start scoring. Only one contest can be active at a time.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">05</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">EDIT CONTESTANTS</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Contestants</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Replace the auto-generated placeholder names (e.g., "Male Contestant 01") with the real names and assigned numbers of each contestant. Make sure contestant numbers match their official competition numbers.
                        </p>
                        <div class="bg-background-secondary/60 border border-border p-4">
                            <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Fields to update</div>
                            <div class="flex gap-6 text-[10px] font-mono text-text-secondary">
                                <span>â€¢ Contestant Number (e.g., M01, F03)</span>
                                <span>â€¢ Full Name</span>
                                <span>â€¢ Active / Inactive status</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 6 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">06</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">DISTRIBUTE JUDGE ACCESS CODES</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Judges</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Each judge has a unique 6-character access code. Go to the Judges section and share each code with the corresponding judge. Judges visit <span class="text-accent">/judge/login</span> and enter their code to access the scoring interface.
                        </p>
                        <div class="bg-background-secondary/60 border border-border p-4 border-l-2 border-warning">
                            <div class="text-[9px] font-mono text-warning uppercase tracking-widest mb-1">Important</div>
                            <p class="text-[10px] font-mono text-text-secondary">Keep access codes confidential. Each code is tied to a specific judge slot. If a code is compromised, you may need to regenerate it.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 7 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">07</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">SET UP EXPOSURES (SEGMENTS)</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Exposures</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Exposures are the scoring rounds/segments of your event (e.g., "Swimwear", "Formal Attire", "Question & Answer"). Each exposure maps to a scoring round that judges will complete. Set the order and activate them one at a time during the event.
                        </p>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-background-secondary/60 border border-border p-3 text-center">
                                <div class="text-success text-[9px] font-mono uppercase tracking-widest mb-1">Active</div>
                                <p class="text-[9px] font-mono text-text-secondary">Currently being scored by judges</p>
                            </div>
                            <div class="bg-background-secondary/60 border border-border p-3 text-center">
                                <div class="text-warning text-[9px] font-mono uppercase tracking-widest mb-1">Pending</div>
                                <p class="text-[9px] font-mono text-text-secondary">Upcoming, not yet started</p>
                            </div>
                            <div class="bg-background-secondary/60 border border-border p-3 text-center">
                                <div class="text-text-secondary text-[9px] font-mono uppercase tracking-widest mb-1">Completed</div>
                                <p class="text-[9px] font-mono text-text-secondary">Scoring done for this segment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 8 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-success flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">08</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">ACTIVATE THE CONTEST & START SCORING</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Contest Settings â†’ Status â†’ Active</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            When everything is ready, go to Contest Settings and change the status to <span class="text-success font-bold">Active</span>. This signals to the system that the contest is live. Judges can now log in and begin submitting scores.
                        </p>
                        <div class="bg-background-secondary/60 border border-border p-4 border-l-2 border-success">
                            <div class="text-[9px] font-mono text-success uppercase tracking-widest mb-2">Event Day Checklist</div>
                            <ul class="text-[10px] font-mono text-text-secondary space-y-1">
                                <li>âœ“ All contestant names are correct</li>
                                <li>âœ“ All judges have their access codes</li>
                                <li>âœ“ All criteria are defined</li>
                                <li>âœ“ Exposures are ordered and ready</li>
                                <li>âœ“ Contest status set to Active</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 9 -->
            <div class="tactical-card p-8">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-accent flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">09</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">MONITOR SCORING IN REAL TIME</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Tabulation</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            During the event, the Tabulation page shows live score progress for all contestants and judges. You can see which judges have submitted scores and monitor overall standings as scoring progresses.
                        </p>
                        <div class="bg-background-secondary/60 border border-border p-4">
                            <p class="text-[10px] font-mono text-text-secondary">Manage active exposures during the event from the Exposures page â€” activate the next segment when ready, and mark completed segments as done.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 10 -->
            <div class="tactical-card p-8 border-t-2 border-success/40">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 bg-success flex items-center justify-center shrink-0 -skew-x-6">
                        <span class="text-white font-bebas text-2xl skew-x-6">10</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl mb-1">VIEW RESULTS & PRINT</h3>
                        <p class="text-[10px] font-mono text-text-secondary uppercase tracking-widest mb-4">Navigate to: Results & Reports</p>
                        <p class="text-[11px] font-mono text-text-secondary leading-relaxed mb-4">
                            Once all scoring is complete, go to Results & Reports to see the final computed rankings. The system tallies scores across all judges and criteria automatically. Use the Print option for an official printout of final results.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Results Page</div>
                                <p class="text-[10px] font-mono text-text-secondary">Full ranking with individual scores per criterion per judge. Useful for tabulation audit.</p>
                            </div>
                            <div class="bg-background-secondary/60 border border-border p-4">
                                <div class="text-[9px] font-mono text-accent uppercase tracking-widest mb-2">Print Results</div>
                                <p class="text-[10px] font-mono text-text-secondary">Generates a clean printable page with final rankings for official announcement.</p>
                            </div>
                        </div>
                        <div class="mt-4 bg-background-secondary/60 border border-border p-4 border-l-2 border-accent">
                            <p class="text-[10px] font-mono text-text-secondary">After the event, set the contest status to <span class="text-text-primary">Completed</span> in Contest Settings to archive it.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Reference -->
        <div class="tactical-card p-8 mt-8 bg-background-secondary/30">
            <h3 class="text-xl mb-6">QUICK REFERENCE â€” SYSTEM FLOW</h3>
            <div class="flex flex-wrap gap-2 items-center text-[10px] font-mono">
                @foreach([
                    'Admin Login' => 'accent',
                    'Define Criteria' => 'text-primary',
                    'Create Contest' => 'text-primary',
                    'Contest Settings & Logo' => 'text-primary',
                    'Edit Contestants' => 'text-primary',
                    'Distribute Judge Codes' => 'text-primary',
                    'Setup Exposures' => 'text-primary',
                    'Activate Contest' => 'success',
                    'Judges Score' => 'text-primary',
                    'Monitor Tabulation' => 'text-primary',
                    'View & Print Results' => 'success',
                    'Archive (Completed)' => 'warning',
                ] as $step => $color)
                <span class="px-3 py-1.5 border border-border bg-background-primary/40 text-{{ $color }} uppercase tracking-wider">{{ $step }}</span>
                @if(!$loop->last)
                <span class="text-accent">â†’</span>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Judge Flow Card -->
        <div class="tactical-card p-8 mt-6 border-l-4 border-border">
            <h3 class="text-xl mb-4">JUDGE WORKFLOW (what judges experience)</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-background-secondary/60 border border-border p-4">
                    <div class="text-accent font-mono text-[9px] uppercase tracking-widest mb-2">1. Login</div>
                    <p class="text-[10px] font-mono text-text-secondary">Visit /judge/login and enter their unique 6-character access code provided by admin.</p>
                </div>
                <div class="bg-background-secondary/60 border border-border p-4">
                    <div class="text-accent font-mono text-[9px] uppercase tracking-widest mb-2">2. View Contestants</div>
                    <p class="text-[10px] font-mono text-text-secondary">See the list of active contestants for the current exposure/segment.</p>
                </div>
                <div class="bg-background-secondary/60 border border-border p-4">
                    <div class="text-accent font-mono text-[9px] uppercase tracking-widest mb-2">3. Submit Scores</div>
                    <p class="text-[10px] font-mono text-text-secondary">Enter scores for each contestant per criterion and submit.</p>
                </div>
                <div class="bg-background-secondary/60 border border-border p-4">
                    <div class="text-accent font-mono text-[9px] uppercase tracking-widest mb-2">4. Repeat per Segment</div>
                    <p class="text-[10px] font-mono text-text-secondary">Repeat scoring for each exposure/segment as the admin activates them.</p>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>


