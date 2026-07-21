@props(['mode' => 'admin'])

<style>
    .ccdi-tour-focus { position: relative !important; z-index: 1010 !important; outline: 2px solid #FF4655 !important; outline-offset: 4px; box-shadow: 0 0 0 9999px rgba(0, 0, 0, .78) !important; transition: outline .2s ease; }
    #ccdi-tour-popover { position: fixed; z-index: 1020; width: min(360px, calc(100vw - 32px)); border: 1px solid #FF4655; background: #161B22; box-shadow: 0 16px 48px rgba(0,0,0,.5); }
    #ccdi-tour-popover::before { content: ''; position: absolute; width: 12px; height: 12px; background: #161B22; border-left: 1px solid #FF4655; border-top: 1px solid #FF4655; transform: rotate(45deg); top: -7px; left: 28px; transition: all .2s ease; }
    #ccdi-tour-popover.ccdi-tour-above::before { top: auto; bottom: -7px; transform: rotate(225deg); }
    #ccdi-tour-popover.ccdi-tour-right-side::before { top: 28px; left: -7px; transform: rotate(-45deg); }
</style>

<div id="ccdi-tour-popover" class="hidden" role="dialog" aria-live="polite" aria-label="Interactive system tour">
    <div class="h-1 bg-accent"><div id="ccdi-tour-progress" class="h-full bg-success transition-all duration-300"></div></div>
    <div class="p-5">
        <div class="flex items-start justify-between gap-4"><div><div id="ccdi-tour-counter" class="mb-2 text-[9px] font-mono tracking-[0.25em] text-accent uppercase"></div><h2 id="ccdi-tour-title" class="text-xl tracking-widest text-white"></h2></div><button type="button" data-tour-skip class="text-[10px] font-mono uppercase text-text-secondary hover:text-white">Skip ×</button></div>
        <p id="ccdi-tour-body" class="mt-4 text-[12px] leading-relaxed text-text-secondary"></p>
        <p id="ccdi-tour-action" class="mt-4 border-l-2 border-success bg-background-primary/50 px-3 py-2 text-[10px] font-mono leading-relaxed text-text-primary"></p>
        <div class="mt-5 flex items-center justify-between"><button type="button" id="ccdi-tour-back" class="text-[10px] font-mono uppercase tracking-widest text-text-secondary hover:text-white">← Back</button><button type="button" id="ccdi-tour-next" class="border border-border px-3 py-2 text-[9px] font-mono uppercase tracking-widest text-white hover:border-accent hover:bg-accent/10 transition-all">Manual Next →</button></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mode = @json($mode), key = `ccdi-interactive-tour-${mode}-v2`;
    const popover = document.getElementById('ccdi-tour-popover');
    if (!popover) return;
    const adminSteps = [
        { selector: 'a[href$="/admin/contests"]', event: 'click', title: 'Create your event', body: 'Every event starts as a contest. Open the contest list to begin the setup.', action: 'Click the highlighted Contests menu item.' },
        { selector: 'a[href$="/admin/contests/create"]', event: 'click', title: 'Start a new contest', body: 'This opens the event setup form where the system creates your initial judges and contestant placeholders.', action: 'Click Init New Contest.' },
        { selector: 'form[action$="/admin/contests"]', event: 'submit', title: 'Enter event details', body: 'Add the contest name, type, judge count, and contestant counts. These numbers create the initial records for you.', action: 'Complete the form, then click Create Contest.', side: 'right' },
        { selector: 'select[name="status"]', event: 'change-active', title: 'Make the contest live', body: 'When all setup is ready, set Status to Active. Only one contest can be active at one time.', action: 'Choose Active in the highlighted Status field.' },
        { selector: 'button[form="contest-settings-form"]', event: 'click', title: 'Save the activation', body: 'The status change only takes effect after saving the contest settings.', action: 'Click Save Changes.' },
        { selector: 'a[href$="/admin/contestants"]', event: 'click', title: 'Check contestants', body: 'Review the generated contestant roster before the event begins.', action: 'Click Contestants to inspect the roster.' },
        { selector: 'a[href$="/admin/exposures"]', event: 'click', title: 'Configure flow', body: 'Exposures are your scoring segments (e.g. Talent, Q&A). Open the node flow to begin.', action: 'Click the highlighted Exposures menu item.' },
        { selector: '#add-exposure-button', event: 'click', title: 'Add a segment', body: 'Create your first scoring segment using the node system.', action: 'Click + ADD EXPOSURE.' },
        { selector: 'a[href*="/criteria"]', event: 'click', title: 'Define criteria', body: 'Each segment needs criteria (e.g. Beauty 40%, Intellect 60%).', action: 'Click Manage criteria on the node card.' },
        { selector: 'a[href$="/admin/judges"]', event: 'click', title: 'Give judges access', body: 'Each judge card contains a unique access code. Give each code to the correct judge.', action: 'Click Judges to view the access codes.' },
        { selector: 'a[href$="/admin/tabulation"]', event: 'click', title: 'Monitor the event', body: 'Tabulation is your live operational view while judges submit their scores.', action: 'Click Tabulation to monitor scoring.' },
        { selector: 'a[href$="/admin/results"]', event: 'click', title: 'Review final results', body: 'When scoring is complete, use Results & Reports to review rankings and print the official result.', action: 'Click Results & Reports to finish the tour.' }
    ];
    const judgeSteps = [
        { selector: '.score-slider', event: 'input', title: 'Score each criterion', body: 'Move the highlighted slider to set a score. The running total updates automatically.', action: 'Adjust this slider to continue.' },
        { selector: 'button[name="save_draft"]', event: 'click', title: 'Save your work', body: 'Use Save Draft when scores still need review. You can return to the same contestant and segment.', action: 'Click Save Draft to continue.' },
        { selector: 'button[name="finalize"]', event: 'click', title: 'Submit final scores', body: 'Send the completed score set only after you have checked each criterion.', action: 'Click Submit Final Scores when ready.' }
    ];
    const steps = mode === 'judge' ? judgeSteps : adminSteps;
    let index = Math.min(Number(localStorage.getItem(`${key}-step`) || 0), steps.length - 1), target;
    const $ = id => document.getElementById(id), title = $('ccdi-tour-title'), body = $('ccdi-tour-body'), action = $('ccdi-tour-action'), counter = $('ccdi-tour-counter'), progress = $('ccdi-tour-progress'), back = $('ccdi-tour-back'), next = $('ccdi-tour-next');
    const clearFocus = () => { if (target) target.classList.remove('ccdi-tour-focus'); target = null; };
    const finish = skipped => { clearFocus(); popover.classList.add('hidden'); localStorage.setItem(key, skipped ? 'skipped' : 'complete'); };
    const advance = () => { if (index === steps.length - 1) return finish(false); index++; localStorage.setItem(`${key}-step`, index); render(); };
    const position = () => {
        if (!target) return;
        const step = steps[index];
        const r = target.getBoundingClientRect(), gap = 16, width = Math.min(360, window.innerWidth - 32);
        let left, top;
        popover.classList.remove('ccdi-tour-above', 'ccdi-tour-right-side');

        if (step.side === 'right') {
            left = r.right + gap;
            top = r.top;
            if (left + width > window.innerWidth - 16) {
                left = Math.max(16, r.left - width - gap);
            } else {
                popover.classList.add('ccdi-tour-right-side');
            }
        } else {
            left = Math.max(16, Math.min(r.left, window.innerWidth - width - 16));
            top = r.bottom + gap;
            if (top + popover.offsetHeight > window.innerHeight - 16) {
                top = Math.max(16, r.top - popover.offsetHeight - gap);
                popover.classList.add('ccdi-tour-above');
            }
        }
        popover.style.left = `${left}px`;
        popover.style.top = `${top}px`;
    };
    const render = () => {
        clearFocus(); const step = steps[index]; target = document.querySelector(step.selector);
        next.textContent = 'Manual Next →';
        if (!target) {
            popover.classList.remove('hidden');
            title.textContent = 'Continue the tour';
            body.textContent = 'Navigate to the next screen using the sidebar; this step will point to the correct control as soon as it is available.';
            action.textContent = step.action;
            counter.textContent = `Step ${index + 1} of ${steps.length}`;
            next.textContent = 'Skip This Step →';
            return;
        }
        target.classList.add('ccdi-tour-focus');
        title.textContent = step.title;
        body.textContent = step.body;
        action.textContent = `DO THIS // ${step.action}`;
        counter.textContent = `Step ${index + 1} of ${steps.length}`;
        progress.style.width = `${((index + 1) / steps.length) * 100}%`;
        back.classList.toggle('invisible', index === 0);
        popover.classList.remove('hidden');
        requestAnimationFrame(position);
    };
    const start = () => { index = 0; localStorage.removeItem(key); localStorage.setItem(`${key}-step`, index); render(); };
    document.querySelectorAll(`[data-onboarding-start="${mode}"]`).forEach(button => button.addEventListener('click', start));
    document.addEventListener('click', event => { const step = steps[index]; if (target && step.event === 'click' && target.contains(event.target)) { if (index === steps.length - 1) localStorage.setItem(key, 'complete'); else localStorage.setItem(`${key}-step`, index + 1); } }, true);
    document.addEventListener('submit', event => { const step = steps[index]; if (target && step.event === 'submit' && target === event.target) localStorage.setItem(`${key}-step`, index + 1); }, true);
    document.addEventListener('change', event => { const step = steps[index]; if (target && step.event === 'change-active' && event.target === target && event.target.value === 'active') advance(); });
    document.addEventListener('input', event => { const step = steps[index]; if (target && step.event === 'input' && event.target === target) advance(); });
    back.addEventListener('click', () => { if (index > 0) { index--; localStorage.setItem(`${key}-step`, index); render(); } });
    next.addEventListener('click', () => advance());
    popover.querySelectorAll('[data-tour-skip]').forEach(button => button.addEventListener('click', () => finish(true)));
    window.addEventListener('resize', position); window.addEventListener('scroll', position, true);
    const tourState = localStorage.getItem(key), savedStep = localStorage.getItem(`${key}-step`);
    if (!tourState && savedStep === null) start(); else if (tourState !== 'complete' && tourState !== 'skipped') render();
});
</script>



